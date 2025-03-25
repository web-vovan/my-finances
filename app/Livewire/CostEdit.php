<?php

namespace App\Livewire;

use App\Adapters\VovanDB;
use App\Models\Category;
use App\Models\Cost;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class CostEdit extends Component
{
    public $cost;
    public $cost2;

    public $price = null;
    public $categoryId = null;
    public $date;
    public $comment = null;
    public $showHideCategories = true;

    public function mount($uuid)
    {
        $this->cost = Cost::where('uuid', $uuid)->firstOrFail();
        $this->cost2 = VovanDB::select("SELECT * FROM costs WHERE uuid = '" . $uuid . "'")[0];

        $this->price = $this->cost->price;
        $this->date = $this->cost->date;
        $this->categoryId = $this->cost->category->id;
        $this->comment = $this->cost->comment;
    }

    public function save()
    {
        $this->cost->update([
            'price' => $this->price,
            'category_id' => $this->categoryId,
            'date' => Carbon::create($this->date ?? Carbon::now())
                ->toDateString(),
            'comment' => $this->comment
        ]);

        // dd(Carbon::create($this->date ?? Carbon::now())->toDateString());
        VovanDB::query("
            UPDATE costs
            SET
                price = " . $this->price . ",
                comment = '" . $this->comment . "',
                category_id = " . $this->categoryId . ",
                date = '" . Carbon::create($this->date ?? Carbon::now())->toDateString() . "'
            WHERE
                uuid = '" . $this->cost2['uuid'] . "'
        ");

        return redirect()->to('/');
    }

    public function selectCategory(int $categoryId)
    {
        $this->categoryId = $categoryId;
    }

    public function render(): View
    {
        return view('livewire.cost-create')
            ->with([
                'categories' => Category::all(),
                'hideCategories' => []
            ]);
    }
}
