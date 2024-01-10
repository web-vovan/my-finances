<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'login' => 'test1',
                'password' => Hash::make('test1'),
                'family_id' => 1
            ],
            [
                'login' => 'test2',
                'password' => Hash::make('test2'),
                'family_id' => 1
            ],
        ]);
    }
}
