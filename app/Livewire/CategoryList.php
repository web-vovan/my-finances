<?php

namespace App\Livewire;

use App\Adapters\VovanDB;
use Livewire\Attributes\On;
use Livewire\Component;

class CategoryList extends Component
{
    #[On('delete-category')]
    public function deleteCategory(string $uuid)
    {
        VovanDB::query("
            DELETE FROM categories
            WHERE uuid = '" . $uuid . "'"
        );

        return redirect(request()->header('Referer'));
    }

    public function render()
    {
        $categories = VovanDB::select("
            SELECT *
            FROM categories
        ");

        return view('livewire.category-list')
            ->with([
                'categories' => $categories,
            ]);
    }
}
