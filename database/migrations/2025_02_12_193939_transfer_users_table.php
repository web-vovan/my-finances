<?php

use App\Models\User;
use App\Adapters\VovanDB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Перенос таблицы users
     */
    public function up(): void
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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        VovanDB::query('DELETE FROM users');
    }
};
