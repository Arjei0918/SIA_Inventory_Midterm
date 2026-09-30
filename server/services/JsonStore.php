<?php
declare(strict_types=1);

namespace Inventory\Services;

final class JsonStore
{
    public function __construct(private string $path) {}

    public function read(): array
    {
        if (!is_file($this->path)) {
            throw new \RuntimeException('Mock data file is missing.');
        }

        $json = file_get_contents($this->path);
        $data = json_decode($json ?: '', true);

        if (!is_array($data) || !isset($data['products'], $data['movements'])) {
            throw new \RuntimeException('Mock data file is invalid.');
        }

        return $data;
    }

    public function write(array $data): void
    {
        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        if ($json === false || file_put_contents($this->path, $json, LOCK_EX) === false) {
            throw new \RuntimeException('Unable to save mock data.');
        }
    }
}
