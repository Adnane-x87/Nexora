<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'slug', 'category_id', 'brand', 'price', 
        'old_price', 'badge', 'stock', 'description', 'image'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
