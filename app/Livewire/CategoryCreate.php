<?php

namespace App\Livewire;

use App\Models\Category;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CategoryCreate extends Component
{
    #[Validate('required')]
    public $name;

    public function save()
    {
        $this->validate();

        $category = new Category();
        $category->name = $this->name;
        $category->save();

        return redirect()->to('/categories');
    }

    public function render()
    {
        return view('livewire.category-create');
    }
}
