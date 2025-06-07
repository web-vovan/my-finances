<?php

namespace App\Adapters;

use Illuminate\Support\Facades\Process;
use RuntimeException;

class VovanDB {
    public static function query(string $sql): string
    {
        $result = Process::path(config('database.connections.vovanDB.driver_path'))
            ->run('./vovanDB ' .  escapeshellarg($sql))
            ->throw();

        $result = json_decode($result->output(), true);

        if ($result['success'] === false) {
            throw new RuntimeException($result['error']);
        }

        return $result['data'];
    }

    public static function select(string $sql): ?array
    {
        $rawData = self::query($sql);

        return json_decode(str_replace('NULL', 'null', $rawData), true);
    }

    public static function selectFirst(string $sql): ?array
    {
        $data = self::select($sql);

        if (is_null($data)) {
            return null;
        }

        return count($data) > 0 ? $data[0] : null;
    }
}