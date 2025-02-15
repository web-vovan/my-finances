<?php

namespace App\Livewire;

use App\Adapters\VovanDB;
use App\Models\Category;
use App\Models\Cost;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Livewire\Component;

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
        Cost::create([
            'price' => $this->price,
            'category_id' => $this->categoryId,
            'user_id' => auth()->user()->id,
            'date' => Carbon::create($this->date ?? Carbon::now())
                ->toDateString(),
            'comment' => $this->comment
        ]);

        VovanDB::query("
            INSERT INTO costs
            (price, comment, user_id, category_id, date)
            VALUES (" 
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
        return view('livewire.cost-create')
            ->with([
                'categories' => Category::query()
                    ->where('is_hide', false)
                    ->orderBy('id')
                    ->get(),
                'hideCategories' => Category::query()
                    ->where('is_hide', true)
                    ->orderBy('id')
                    ->get(),
            ]);
    }
}
