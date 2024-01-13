<?php

namespace App\Livewire;

use App\Models\Category;
use Livewire\Component;

class ShowCategory extends Component
{
    public function render()
    {
        return view('livewire.show-category')
            ->with([
                'categories' => Category::all()
            ]);
    }
}
