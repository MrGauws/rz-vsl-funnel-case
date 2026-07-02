<?php

namespace App\Support;

class Store
{
    private string $path;

    public function __construct(string $path)
    {
        $this->path = $path;
        if (!file_exists($path)) {
            file_put_contents($path, json_encode([
                'events' => [],
                'orders' => [],
                'access' => [],
            ], JSON_PRETTY_PRINT));
        }
    }

    public function all(): array
    {
        $raw = file_get_contents($this->path);
        $data = json_decode($raw ?: '{}', true);

        return is_array($data) ? array_merge([
            'events' => [],
            'orders' => [],
            'access' => [],
        ], $data) : ['events' => [], 'orders' => [], 'access' => []];
    }

    public function save(array $data): void
    {
        file_put_contents($this->path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    public function push(string $collection, array $record): array
    {
        $data = $this->all();
        $record['id'] = count($data[$collection]) + 1;
        $record['created_at'] = gmdate('c');
        $data[$collection][] = $record;
        $this->save($data);

        return $record;
    }
}

