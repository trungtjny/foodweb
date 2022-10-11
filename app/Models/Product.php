<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'category_id',
        'thumb',
        'price',
        'sold',
        'description',
        'product_content',
        'is_show',
        'options',
        'has_option'
    ];

    public function category(){
        return  $this->hasOne(Category::class);
    }

    protected $casts = [
        'options'    => 'json',
    ];

}
