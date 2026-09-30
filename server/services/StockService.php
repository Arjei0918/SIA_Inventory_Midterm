<?php
declare(strict_types=1);

namespace Inventory\Services;

final class StockService
{
    public function __construct(private JsonStore $store) {}

    public function movements(): array
    {
        $data = $this->store->read();
        $products = [];
        foreach ($data['products'] as $product) $products[$product['id']] = $product['product_name'];

        usort($data['movements'], fn($a, $b) => $b['id'] <=> $a['id']);
        return array_map(function ($movement) use ($products) {
            return [
                ...$movement,
                'product_name' => $products[$movement['product_id']] ?? 'Unknown'
            ];
        }, $data['movements']);
    }

    public function create(array $input): array
    {
        $data = $this->store->read();
        $productId = (int)$input['product_id'];

        foreach ($data['products'] as $i => $product) {
            if ($product['id'] !== $productId) continue;

            $quantity = (int)$input['quantity'];
            $type = strtoupper((string)$input['movement_type']);
            $newStock = $type === 'IN'
                ? $product['stock'] + $quantity
                : $product['stock'] - $quantity;

            if ($type === 'OUT' && $quantity > $product['stock']) {
                throw new \InvalidArgumentException('Stock out quantity cannot exceed current stock.');
            }

            $movementIds = array_column($data['movements'], 'id');
            $movement = [
                'id' => $movementIds ? max($movementIds) + 1 : 1,
                'product_id' => $productId,
                'movement_type' => $type,
                'quantity' => $quantity,
                'remarks' => trim((string)($input['remarks'] ?? '')),
                'created_at' => date('c')
            ];

            $data['products'][$i]['stock'] = $newStock;
            $data['movements'][] = $movement;
            $this->store->write($data);

            return [
                'movement' => $movement,
                'product' => $data['products'][$i]
            ];
        }

        return [];
    }

    public function integrationInventory(): array
    {
        return array_map(fn($p) => [
            'productId' => 'INV-' . str_pad((string)$p['id'], 5, '0', STR_PAD_LEFT),
            'productName' => $p['product_name'],
            'category' => $p['category'],
            'unitPrice' => $p['price'],
            'stockQuantity' => $p['stock'],
            'availability' => $p['stock'] > 0 ? 'Available' : 'Out of Stock',
            'lowStock' => $p['stock'] <= 10
        ], $this->store->read()['products']);
    }
}
