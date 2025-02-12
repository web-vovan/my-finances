<?php

use App\Models\Cost;
use App\Adapters\VovanDB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Перенос таблицы costs
     */
    public function up(): void
    {
        $costs = Cost::all();
        $countCategories = count($costs);

        $sql = 'INSERT INTO costs (id, price, comment, user_id, category_id, date) VALUES ';

        foreach ($costs as $key => $cost) {
            $sql .= sprintf(
                "(%d, %d, '%s', %d, %d, '%s')",
                $cost->id,
                $cost->price,
                $cost->comment,
                $cost->user_id,
                $cost->category_id,
                $cost->date->format('Y-m-d')
            );

            if ($key < $countCategories - 1) {
                $sql .= ',';
            }
        }

        VovanDB::query($sql);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        VovanDB::query('DELETE FROM costs');
    }
};
