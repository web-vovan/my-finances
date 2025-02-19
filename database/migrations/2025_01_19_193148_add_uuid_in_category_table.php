<?php

use Ramsey\Uuid\Uuid;
use App\Models\Category;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table
                ->uuid('uuid')
                ->after('id');
        });

        $categories = Category::all();

        foreach ($categories as $category) {
            $category->uuid = Uuid::uuid4();
            $category->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
};
