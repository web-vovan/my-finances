<?php

use App\Models\Cost;
use Ramsey\Uuid\Uuid;
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
        Schema::table('costs', function (Blueprint $table) {
            $table
                ->uuid('uuid')
                ->after('id');
        });

        $costs = Cost::all();

        foreach ($costs as $cost) {
            $cost->uuid = Uuid::uuid4();
            $cost->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('costs', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
};
