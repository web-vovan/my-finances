<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Cost;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;

class CreateCost extends Component
{
    public $price = null;
    public $categoryId = null;
    public $date = null;
    public $comment = null;

    #[Computed]
    public function customDate()
    {
        return Carbon::now()->toDateString();
    }

    public function save()
    {
        Cost::create([
            'price' => $this->price,
            'category_id' => $this->categoryId,
            'user_id' => 1,
            'date' => Carbon::create($this->date)->toDateString(),
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
        return view('livewire.create-cost')
            ->with([
                'categories' => Category::all(),
            ]);
    }
}
