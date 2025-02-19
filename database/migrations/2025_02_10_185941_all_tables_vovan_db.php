<?php

use App\Adapters\VovanDB;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
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
     * Run the migrations.
     */
    public function up(): void
    {
        foreach ($this->createSqlArr as $sql) {
            VovanDB::query($sql);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach ($this->dropSqlArr as $sql) {
            VovanDB::query($sql);
        }
    }
};
