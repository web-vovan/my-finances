<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Создание пользователя';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $login = $this->ask('Введите логин');

        if (is_null($login)) {
            $this->error('Логин не может быть пустым');
            return;
        }

        $user = User::query()->where('login', $login)->first();

        if ($user) {
            $this->error('Пользователь с таким логином уже существует');
            return;
        }

        $password = $this->ask('Введите пароль');

        if (is_null($password)) {
            $this->error('Пароль не может быть пустым');
            return;
        }

        if ($this->confirm('Создать пользователя с логином: '  . $login . ' и паролем: ' . $password, true)) {
            $user = new User();

            $user->login = $login;
            $user->password = $password;

            $user->save();

            $this->info('Пользователь успешно создан');
        }
    }
}
