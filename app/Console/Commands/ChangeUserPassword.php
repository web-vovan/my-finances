<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ChangeUserPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:change-password';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Смена пароля пользователя';

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

        if (is_null($user)) {
            $this->error('Пользователь с таким логином не существует');
            return;
        }

        $password = $this->ask('Введите пароль');

        if (is_null($password)) {
            $this->error('Пароль не может быть пустым');
            return;
        }

        if ($this->confirm('Заменить пароль на: ' . $password, true)) {
            $user->password = $password;

            $user->save();

            $this->info('Пароль успешно изменен');
        }
    }
}
