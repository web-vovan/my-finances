<?php

namespace Database\Seeders;

use App\Models\Cost;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            FamilySeeder::class,
            UserSeeder::class,
            CategorySeeder::class,
        ]);

        Cost::factory()
            ->count(500)
            ->create();
    }
}
