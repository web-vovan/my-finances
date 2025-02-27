<?php

namespace App\Livewire;

use App\Adapters\VovanDB;
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
        $categories = Category::all();
        $categories2 = VovanDB::select("
            SELECT *
            FROM categories
        ");

        return view('livewire.category-list')
            ->with([
                'categories' => $categories,
                'categories2' => $categories2
            ]);
    }
}
