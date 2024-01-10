<?php

namespace App\Console\Commands;

use App\Models\Family;
use App\Models\User;
use Illuminate\Console\Command;

class AttachUserToFamily extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:family';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Привязка пользователя к семейной учетке';

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

        $families = Family::all();

        $selectFamily = $this->choice(
            'Выберите семейную учетку',
            $families->pluck('name')->toArray()
        );

        $family = $families->first(fn ($item) => $item->name === $selectFamily);

        if ($this->confirm('Прикрепить пользователя: ' . $login . ' к семейной учетке: ' . $selectFamily, true)) {
            $user->family()->associate($family);
            $user->save();

            $this->info('Пользователь успешно изменен');
        }
    }
}
