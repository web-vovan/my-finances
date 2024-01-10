<?php

namespace App\Console\Commands;

use App\Models\Family;
use Illuminate\Console\Command;

class CreateFamily extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'family:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Создание семейной учетки';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->ask('Введите название семейной учетки');

        if (is_null($name)) {
            $this->error('Название не может быть пустым');
            return;
        }

        $family = Family::query()->where('name', $name)->first();

        if ($family) {
            $this->error('Такая учетка уже существует');
        }

        if ($this->confirm('Создать семейную учетку: '  . $name, true)) {
            $family = new Family();
            $family->name = $name;

            $family->save();

            $this->info('Семейная учетка успешно создана');
        }
    }
}
