<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $table = 'carts';
    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'product_options'
    ];
    protected $hidden = [
        'created_at', 'updated_at', 'user_id', 'product_id',
    ];
    protected $casts = [
        'product_options'    => 'json',
    ];
    public function products(){
        return $this->belongsTo(Product::class,'product_id','id');
    }
}
