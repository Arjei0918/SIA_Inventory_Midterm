<?php
declare(strict_types=1);

use Inventory\Services\ProductService;
use Inventory\Services\ProblemDetails;
use Inventory\Services\StockService;
use Slim\App;

return function (App $app, StockService $stock, ProductService $products): void {
    $app->get('/api/v1/stock/movements', function ($request, $response) use ($stock) {
        return jsonResponse($response, ['data' => $stock->movements()]);
    });

    $app->post('/api/v1/stock/movements', function ($request, $response) use ($stock, $products) {
        $input = jsonBody($request);
        foreach (['product_id', 'movement_type', 'quantity'] as $field) {
            if (!array_key_exists($field, $input)) {
                return ProblemDetails::send($response, 400, 'Validation error', "The {$field} field is required.", (string)$request->getUri()->getPath());
            }
        }

        $type = strtoupper((string)$input['movement_type']);
        if (!is_numeric($input['product_id']) || (int)$input['product_id'] < 1 ||
            !in_array($type, ['IN', 'OUT'], true) ||
            !is_numeric($input['quantity']) || (int)$input['quantity'] < 1) {
            return ProblemDetails::send($response, 400, 'Validation error', 'Product ID must be positive, movement type must be IN or OUT, and quantity must be at least 1.', (string)$request->getUri()->getPath());
        }

        $product = $products->find((int)$input['product_id']);
        if (!$product) {
            return ProblemDetails::send($response, 404, 'Product not found', 'No product exists with the requested ID.', (string)$request->getUri()->getPath());
        }

        try {
            $result = $stock->create([
                ...$input,
                'movement_type' => $type
            ]);
        } catch (\InvalidArgumentException $e) {
            return ProblemDetails::send($response, 400, 'Stock validation error', $e->getMessage(), (string)$request->getUri()->getPath());
        }

        return jsonResponse($response, ['data' => $result], 201);
    });
};
