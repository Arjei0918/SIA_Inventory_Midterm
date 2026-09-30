<?php
declare(strict_types=1);

use Inventory\Services\JsonStore;
use Inventory\Services\ProductService;
use Inventory\Services\StockService;
use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../routes/helpers.php';

$base = dirname(__DIR__);
$store = new JsonStore($base . '/data/store.json');
$products = new ProductService($store);
$stock = new StockService($store);

$app = AppFactory::create();
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();

$errorMiddleware = $app->addErrorMiddleware(false, true, true);
$errorMiddleware->setErrorHandler(
    \Slim\Exception\HttpNotFoundException::class,
    function ($request, $exception, $displayErrorDetails, $logErrors, $logErrorDetails) {
        $response = new \Slim\Psr7\Response(404);
        return jsonResponse($response, [
            'type' => 'about:blank',
            'title' => 'Not found',
            'status' => 404,
            'detail' => 'The requested resource was not found.',
            'instance' => $request->getUri()->getPath()
        ], 404)->withHeader('Content-Type', 'application/problem+json');
    }
);

$app->add(function (ServerRequestInterface $request, $handler): ResponseInterface {
    if ($request->getMethod() === 'OPTIONS') {
        $response = new \Slim\Psr7\Response(204);
        return cors($response);
    }
    return cors($handler->handle($request));
});

(require __DIR__ . '/../routes/health.php')($app);
(require __DIR__ . '/../routes/products.php')($app, $products);
(require __DIR__ . '/../routes/stock.php')($app, $stock, $products);
(require __DIR__ . '/../routes/integration.php')($app, $stock);

$app->run();
