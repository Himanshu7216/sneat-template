<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Products extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name',
        'sku',
        'description',
        'color',
        'size',
        'image',
        'price',
        'category_id',
    ];
    protected $casts = [
        'image' => 'array',
    ];

    function Category(){
        return $this->belongsTo(Category::class);
    }
}
