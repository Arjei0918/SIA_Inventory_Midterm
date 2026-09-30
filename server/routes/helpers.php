<?php
declare(strict_types=1);

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

function jsonBody(ServerRequestInterface $request): array
{
    $parsed = $request->getParsedBody();

    return is_array($parsed) ? $parsed : [];
}

function jsonResponse(ResponseInterface $response, array $payload, int $status = 200): ResponseInterface
{
    $response->getBody()->write(json_encode($payload, JSON_UNESCAPED_SLASHES));
    return $response
        ->withStatus($status)
        ->withHeader('Content-Type', 'application/json');
}

function cors(ResponseInterface $response): ResponseInterface
{
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
        ->withHeader('Access-Control-Allow-Headers', 'Content-Type');
}
