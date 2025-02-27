<?php

namespace App\Livewire;

use App\Adapters\VovanDB;
use Ramsey\Uuid\Uuid;
use Livewire\Component;
use App\Models\Category;
use Livewire\Attributes\Validate;

class CategoryCreate extends Component
{
    #[Validate('required')]
    public $name;
    public $isHide = false;

    public function save()
    {
        $this->validate();

        $uuid = Uuid::uuid4();

        $category = new Category();
        $category->name = $this->name;
        $category->is_hide = $this->isHide;
        $category->uuid = $uuid;
        $category->save();

        VovanDB::query("
            INSERT INTO categories (uuid, name, is_hide)
            VALUES (
                '" . $uuid . "', '" . $this->name . "'," . ($this->isHide ? 'true' : 'false') . "
            )
        ");

        return redirect()->to('/categories');
    }

    public function render()
    {
        return view('livewire.category-create');
    }
}
