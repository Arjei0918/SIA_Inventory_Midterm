<?php
declare(strict_types=1);

use Inventory\Services\StockService;
use Slim\App;

return function (App $app, StockService $stock): void {
    $app->get('/api/v1/integration/inventory', function ($request, $response) use ($stock) {
        return jsonResponse($response, [
            'source' => 'Inventory',
            'data' => $stock->integrationInventory()
        ]);
    });
};
