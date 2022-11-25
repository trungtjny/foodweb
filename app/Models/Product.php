<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use PhpParser\JsonDecoder;

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
        'has_option',
        'sale'
    ];

    protected $casts = [
        'options' => 'array',
    ];
   
    public function category(){
        return  $this->hasOne(Category::class);
    }

    // public function setOptionsAttribute($pass)
    // {
    //     logger(json_encode($pass));
    //     $this->attributes['options'] = json_decode($pass);
    // }
    // public function getOptionsAttribute()
    // {
    //     logger(json_decode($this->attributes['options']));
    //     return  json_decode($this->attributes['options']);
    // }
}
