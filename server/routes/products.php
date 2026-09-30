<?php
declare(strict_types=1);

use Inventory\Services\ProductService;
use Inventory\Services\ProblemDetails;
use Slim\App;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

return function (App $app, ProductService $products): void {
    $app->get('/api/v1/products', function ($request, $response) use ($products) {
        return jsonResponse($response, ['data' => $products->all()]);
    });

    $app->get('/api/v1/products/{id}', function ($request, $response, $args) use ($products) {
        if (!ctype_digit($args['id']) || (int)$args['id'] < 1) {
            return ProblemDetails::send($response, 400, 'Invalid product ID', 'Product ID must be a positive integer.', (string)$request->getUri()->getPath());
        }
        $product = $products->find((int)$args['id']);
        if (!$product) {
            return ProblemDetails::send($response, 404, 'Product not found', 'No product exists with the requested ID.', (string)$request->getUri()->getPath());
        }
        return jsonResponse($response, ['data' => $product]);
    });

    $app->post('/api/v1/products', function ($request, $response) use ($products) {
        $input = jsonBody($request);
        $required = ['product_name', 'category', 'price', 'stock'];
        foreach ($required as $field) {
            if (!array_key_exists($field, $input)) {
                return ProblemDetails::send($response, 400, 'Validation error', "The {$field} field is required.", (string)$request->getUri()->getPath());
            }
        }
        if (trim((string)$input['product_name']) === '' || trim((string)$input['category']) === '' ||
            !is_numeric($input['price']) || !is_numeric($input['stock']) ||
            (float)$input['price'] < 0 || (int)$input['stock'] < 0) {
            return ProblemDetails::send($response, 400, 'Validation error', 'Name and category are required; price and stock must be non-negative numbers.', (string)$request->getUri()->getPath());
        }
        return jsonResponse($response, ['data' => $products->create($input)], 201);
    });

    $app->put('/api/v1/products/{id}', function ($request, $response, $args) use ($products) {
        if (!ctype_digit($args['id']) || (int)$args['id'] < 1) {
            return ProblemDetails::send($response, 400, 'Invalid product ID', 'Product ID must be a positive integer.', (string)$request->getUri()->getPath());
        }
        $id = (int)$args['id'];
        if (!$products->find($id)) {
            return ProblemDetails::send($response, 404, 'Product not found', 'No product exists with the requested ID.', (string)$request->getUri()->getPath());
        }
        $input = jsonBody($request);
        foreach (['product_name', 'category', 'price', 'stock'] as $field) {
            if (!array_key_exists($field, $input)) {
                return ProblemDetails::send($response, 400, 'Validation error', "The {$field} field is required.", (string)$request->getUri()->getPath());
            }
        }
        if (trim((string)$input['product_name']) === '' || trim((string)$input['category']) === '' ||
            !is_numeric($input['price']) || !is_numeric($input['stock']) ||
            (float)$input['price'] < 0 || (int)$input['stock'] < 0) {
            return ProblemDetails::send($response, 400, 'Validation error', 'Name and category are required; price and stock must be non-negative numbers.', (string)$request->getUri()->getPath());
        }
        return jsonResponse($response, ['data' => $products->update($id, $input)]);
    });

    $app->delete('/api/v1/products/{id}', function ($request, $response, $args) use ($products) {
        if (!ctype_digit($args['id']) || (int)$args['id'] < 1) {
            return ProblemDetails::send($response, 400, 'Invalid product ID', 'Product ID must be a positive integer.', (string)$request->getUri()->getPath());
        }
        if (!$products->delete((int)$args['id'])) {
            return ProblemDetails::send($response, 404, 'Product not found', 'No product exists with the requested ID.', (string)$request->getUri()->getPath());
        }
        return jsonResponse($response, ['message' => 'Product deleted successfully.']);
    });
};
