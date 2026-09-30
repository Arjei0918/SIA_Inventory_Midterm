<?php
declare(strict_types=1);

use Slim\App;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

return function (App $app): void {
    $app->get('/api/v1/health', function (ServerRequestInterface $request, ResponseInterface $response) {
        return jsonResponse($response, ['status' => 'ok']);
    });
};
