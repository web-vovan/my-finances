<?php

use App\Models\Family;
use App\Adapters\VovanDB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
     /**
     * Перенос таблицы families
     */
    public function up(): void
    {
        $families = Family::all();
        $countFamilies = count($families);

        $sql = 'INSERT INTO families (id, name) VALUES ';

        foreach ($families as $key => $family) {
            $sql .= sprintf(
                "(%d, '%s')",
                $family->id,
                $family->name
            );

            if ($key < $countFamilies - 1) {
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
        VovanDB::query('DELETE FROM families');
    }
};
