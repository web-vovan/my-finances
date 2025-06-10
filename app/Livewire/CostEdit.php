<?php

namespace App\Livewire;

use App\Adapters\VovanDB;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class CostEdit extends Component
{
    public $cost;

    public $price = null;
    public $categoryId = null;
    public $date;
    public $comment = null;
    public $showHideCategories = true;

    public function mount($uuid)
    {
        $this->cost = VovanDB::select("SELECT * FROM costs WHERE uuid = '" . $uuid . "'")[0];
        $this->price = $this->cost['price'];
        $this->date = $this->cost['date'];
        $this->categoryId = $this->cost['category_id'];
        $this->comment = $this->cost['comment'];
    }

    public function save()
    {
        VovanDB::query("
            UPDATE costs
            SET
                price = " . $this->price . ",
                comment = '" . $this->comment . "',
                category_id = " . $this->categoryId . ",
                date = '" . Carbon::create($this->date ?? Carbon::now())->toDateString() . "'
            WHERE
                uuid = '" . $this->cost['uuid'] . "'
        ");

        return redirect()->to('/');
    }

    public function selectCategory(int $categoryId)
    {
        $this->categoryId = $categoryId;
    }

    public function render(): View
    {
        $categories = VovanDB::select("
            SELECT * 
            FROM categories 
            WHERE is_hide = false"
        );

        return view('livewire.cost-create')
            ->with([
                'categories' => $categories,
                'hideCategories' => []
            ]);
    }
}
