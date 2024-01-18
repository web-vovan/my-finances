<?php

namespace App\Livewire;

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

    public function mount()
    {
        $this->date = Carbon::now();
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
            ]);
    }
}
