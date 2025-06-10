<?php

namespace App\Livewire;

use App\Adapters\VovanDB;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Ramsey\Uuid\Uuid;

class CostCreate extends Component
{
    public $price = null;
    public $categoryId = null;
    public $date;
    public $comment = null;
    public $showHideCategories = false;

    public function mount()
    {
        $this->date = Carbon::now();
        $this->categoryId = 1;
    }

    public function save()
    {
        $uuid = Uuid::uuid4();

        VovanDB::query("
            INSERT INTO costs
            (uuid, price, comment, user_id, category_id, date)
            VALUES (" 
            . "'" . $uuid . "'," 
            . $this->price . ","
            . "'" . $this->comment . "'," 
            . auth()->user()->id . ","
            . $this->categoryId . ","
            . "'" . Carbon::create($this->date ?? Carbon::now())
                ->toDateString() . "'"
            . ")"
        );

        return redirect()->to('/');
    }

    public function selectCategory(int $categoryId)
    {
        $this->categoryId = $categoryId;
    }

    public function activeHideCategories(): bool
    {
        return $this->showHideCategories = true;
    }

    public function render(): View
    {
        $notHideCategories = VovanDB::select("
            SELECT * 
            FROM categories 
            WHERE is_hide = false"
        );

        $hideCategories = VovanDB::select("
            SELECT * 
            FROM categories 
            WHERE is_hide = true"
        );

        return view('livewire.cost-create')
            ->with([
                'categories' => $notHideCategories,
                'hideCategories' => $hideCategories,
            ]);
    }
}
