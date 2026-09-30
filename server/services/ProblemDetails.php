<?php
declare(strict_types=1);

namespace Inventory\Services;

use Psr\Http\Message\ResponseInterface;

final class ProblemDetails
{
    public static function send(
        ResponseInterface $response,
        int $status,
        string $title,
        string $detail,
        string $instance
    ): ResponseInterface {
        $payload = [
            'type' => 'about:blank',
            'title' => $title,
            'status' => $status,
            'detail' => $detail,
            'instance' => $instance
        ];

        $response->getBody()->write(json_encode($payload, JSON_UNESCAPED_SLASHES));
        return $response
            ->withStatus($status)
            ->withHeader('Content-Type', 'application/problem+json');
    }
}
