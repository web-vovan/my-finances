<?php

namespace App\Livewire;

use App\Models\Category;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CategoryEdit extends Component
{
    public $category;

    #[Validate('required')]
    public $name;

    public function mount($id)
    {
        $this->category = Category::findOrFail($id);
        $this->name = $this->category->name;
    }

    public function save()
    {
        $this->validate();

        $this->category->name = $this->name;
        $this->category->save();

        return redirect()->to('/categories');
    }

    public function render()
    {
        return view('livewire.category-edit');
    }
}
