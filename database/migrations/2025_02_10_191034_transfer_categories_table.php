<?php

use App\Adapters\VovanDB;
use App\Models\Category;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Перенос таблицы categories
     */
    public function up(): void
    {
        $categories = Category::all();
        $countCategories = count($categories);

        $sql = 'INSERT INTO categories (id, name, is_hide) VALUES ';

        foreach ($categories as $key => $category) {
            $sql .= '(' . $category->id . ",'" . $category->name . "'," . ($category->is_hide ? 'true' : 'false') . ')';

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
        VovanDB::query('DELETE FROM categories');
    }
};
