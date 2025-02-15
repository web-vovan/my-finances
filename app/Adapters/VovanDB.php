<?php

namespace App\Adapters;

use Illuminate\Support\Facades\Process;
use RuntimeException;

class VovanDB {
    public static function query(string $sql): string
    {
        $result = Process::path(base_path('database'))
            ->run('./vovanDB ' .  escapeshellarg($sql))
            ->throw();

        $result = json_decode($result->output(), true);

        if ($result['success'] === false) {
            throw new RuntimeException($result['error']);
        }

        return $result['data'];
    }

    public static function select(string $sql): array
    {
        return json_decode(self::query($sql), true);
    }
}