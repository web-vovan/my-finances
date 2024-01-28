<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Cost;
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

    public function mount($id)
    {
        $this->cost = Cost::findOrFail($id);

        $this->price = $this->cost->price;
        $this->date = $this->cost->date;
        $this->categoryId = $this->cost->category?->id;
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
