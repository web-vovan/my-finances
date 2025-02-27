<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'uuid',
        'is_hide'
    ];

    protected $casts = [
        'is_hide' => 'boolean'
    ];

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function costs(): HasMany
    {
        return $this->hasMany(Cost::class);
    }
}
