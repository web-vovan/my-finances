<?php

namespace App\Livewire;

use App\Models\Category;
use Livewire\Attributes\On;
use Livewire\Component;

class CategoryList extends Component
{
    #[On('delete-category')]
    public function deleteCategory(int $categoryId)
    {
        Category::destroy($categoryId);

        return redirect(request()->header('Referer'));
    }

    public function render()
    {
        return view('livewire.category-list')
            ->with([
                'categories' => Category::all()
            ]);
    }
}
