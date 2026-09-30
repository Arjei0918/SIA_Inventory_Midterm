<?php
declare(strict_types=1);

namespace Inventory\Services;

final class ProductService
{
    public function __construct(private JsonStore $store) {}

    public function all(): array
    {
        $data = $this->store->read();
        usort($data['products'], fn($a, $b) => $b['id'] <=> $a['id']);
        return $data['products'];
    }

    public function find(int $id): ?array
    {
        foreach ($this->store->read()['products'] as $product) {
            if ($product['id'] === $id) return $product;
        }
        return null;
    }

    public function create(array $input): array
    {
        $data = $this->store->read();
        $ids = array_column($data['products'], 'id');
        $id = $ids ? max($ids) + 1 : 1;

        $product = [
            'id' => $id,
            'product_name' => trim((string)$input['product_name']),
            'category' => trim((string)$input['category']),
            'price' => (float)$input['price'],
            'stock' => (int)$input['stock'],
            'created_at' => date('c')
        ];

        $data['products'][] = $product;
        $this->store->write($data);
        return $product;
    }

    public function update(int $id, array $input): ?array
    {
        $data = $this->store->read();
        foreach ($data['products'] as $i => $product) {
            if ($product['id'] === $id) {
                $data['products'][$i] = [
                    ...$product,
                    'product_name' => trim((string)$input['product_name']),
                    'category' => trim((string)$input['category']),
                    'price' => (float)$input['price'],
                    'stock' => (int)$input['stock']
                ];
                $this->store->write($data);
                return $data['products'][$i];
            }
        }
        return null;
    }

    public function delete(int $id): bool
    {
        $data = $this->store->read();
        $before = count($data['products']);
        $data['products'] = array_values(array_filter(
            $data['products'],
            fn($product) => $product['id'] !== $id
        ));

        if (count($data['products']) === $before) return false;

        $this->store->write($data);
        return true;
    }
}
