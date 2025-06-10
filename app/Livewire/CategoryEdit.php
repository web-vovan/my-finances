<?php

namespace App\Livewire;

use App\Adapters\VovanDB;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CategoryEdit extends Component
{
    public $category;
    public $category2;

    #[Validate('required')]
    public $name;
    public $isHide;

    public function mount($uuid)
    {
        $this->category = VovanDB::select("SELECT * FROM categories WHERE uuid = '" . $uuid . "'")[0];

        $this->name = $this->category['name'];
        $this->isHide = $this->category['is_hide'];
    }

    public function save()
    {
        $this->validate();

        VovanDB::query("
            UPDATE categories
            SET
                name = '" . $this->name . "',
                is_hide = " . ($this->isHide ? 'true' : 'false') . "
            WHERE
                uuid = '" . $this->category['uuid'] . "'
        ");

        return redirect()->to('/categories');
    }

    public function render()
    {
        return view('livewire.category-edit');
    }
}
