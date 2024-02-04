<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    protected $casts = [
        'date' => 'date'
    ];

    public function getPriceFormatAttribute(): string
    {
        return priceFormat($this->price);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
