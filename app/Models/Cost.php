<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Number;

class Cost extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'price',
        'comment',
        'category_id',
        'user_id',
        'date'
    ];

    public function priceFormat(): string
    {
        return number_format($this->price, 0, '', ' ') . ' ₽';
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
