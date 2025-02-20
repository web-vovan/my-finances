<?php

namespace App\Console\Commands;

use App\Models\Cost;
use App\Models\User;
use App\Models\Family;
use App\Models\Category;
use App\Adapters\VovanDB;
use Illuminate\Console\Command;

class DataSync extends Command
{
    private array $createSqlArr = [
        '
        -- myfinance.costs определение

        CREATE TABLE `costs` (
        `id` int NOT NULL AUTO_INCREMENT,
        `uuid` text NOT NULL,
        `price` int NOT NULL,
        `comment` text,
        `user_id` int NOT NULL,
        `category_id` int,
        `date` date NOT NULL)
        ',
        '
        -- myfinance.categories определение

        CREATE TABLE `categories` (
        `id` int NOT NULL AUTO_INCREMENT,
        `uuid` text NOT NULL,
        `name` text NOT NULL,
        `is_hide` bool NOT NULL,);',
        '
        -- myfinance.families определение

        CREATE TABLE `families` (
        `id` int NOT NULL AUTO_INCREMENT,
        `name` text NOT NULL);
        ',
        '
        -- myfinance.migrations определение

        CREATE TABLE `migrations` (
        `id` int NOT NULL AUTO_INCREMENT,
        `migration` text NOT NULL,
        `batch` int NOT NULL);
        ',
        '
        -- myfinance.users определение

        CREATE TABLE `users` (
        `id` int NOT NULL AUTO_INCREMENT,
        `name` text null,
        `email` text,
        `login` text NOT NULL,
        `email_verified_at` datetime NULL,
        `password` text NOT NULL,
        `family_id` int null,
        `remember_token` text NULL,
        `created_at` datetime NULL,
        `updated_at` datetime NULL);
        ',
    ];

    private array $dropSqlArr = [
        'DROP TABLE costs',
        'DROP TABLE categories',
        'DROP TABLE migrations',
        'DROP TABLE users',
        'DROP TABLE families',
    ];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:data-sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Синхронизация данных в postgreSQL и vovanDB';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->dropAllTables();
        $this->createAllTables();

        $this->transferCategoriesData();
        $this->transferCostsData();
        $this->transferFamiliesData();
        $this->transferUsersData();
    }

    /**
     * Удаление всех таблиц в vovanDB
     */
    private function dropAllTables(): void
    {
        try {
            foreach ($this->dropSqlArr as $sql) {
                VovanDB::query($sql);
            }
        } catch (\Throwable $e) {
        }
    }

    /**
     * Создание всех таблиц в vovanDB
     */
    private function createAllTables(): void
    {
        foreach ($this->createSqlArr as $sql) {
            VovanDB::query($sql);
        }
    }

    /**
     * Перенос таблицы categories
     */
    private function transferCategoriesData(): void
    {
        $categories = Category::all();
        $countCategories = count($categories);

        $sql = 'INSERT INTO categories (id, uuid, name, is_hide) VALUES ';

        foreach ($categories as $key => $category) {
            $sql .= '(' . $category->id . ",'" . $category->uuid . "'," . ",'" . $category->name . "'," . ($category->is_hide ? 'true' : 'false') . ')';

            if ($key < $countCategories - 1) {
                $sql .= ',';
            }
        }

        VovanDB::query($sql);
    }

    /**
     * Перенос таблицы costs
     */
    private function transferCostsData(): void
    {
        $costs = Cost::all();
        $countCategories = count($costs);

        $sql = 'INSERT INTO costs (id, uuid, price, comment, user_id, category_id, date) VALUES ';

        foreach ($costs as $key => $cost) {
            $sql .= sprintf(
                "(%d, '%s', %d, '%s', %d, %d, '%s')",
                $cost->id,
                $cost->uuid,
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
     * Перенос таблицы families
     */
    private function transferFamiliesData(): void
    {
        $families = Family::all();
        $countFamilies = count($families);

        if ($countFamilies === 0) {
            return;
        }
        
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
     * Перенос таблицы users
     */
    private function transferUsersData(): void
    {
        $users = User::all();
        $countUsers = count($users);

        $sql = 'INSERT INTO users (
            id,
            name,
            email,
            login,
            email_verified_at,
            password,
            family_id,
            remember_token,
            created_at,
            updated_at
        ) VALUES ';

        foreach ($users as $key => $user) {
            $sql .= sprintf(
                "(%d, '%s', '%s', '%s', %s, '%s', %d, '%s', %s, %s)",
                $user->id,
                $user->name,
                $user->email,
                $user->login,
                $user->email_verified_at ? "'" . $user->email_verified_at . "'" : 'null',
                $user->password,
                $user->family_id,
                $user->remember_token,
                $user->created_at ? "'" . $user->created_at . "'" : 'null',
                $user->updated_at ? "'" . $user->updated_at . "'" : 'null' 
            );

            if ($key < $countUsers - 1) {
                $sql .= ',';
            }
        }

        VovanDB::query($sql);
    }
}
