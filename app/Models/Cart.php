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
        'quantity'
    ];
    protected $hidden = [
        'created_at', 'updated_at', 'user_id', 'product_id',
    ];
    public function products(){
        return $this->belongsTo(Product::class,'product_id','id');
    }
}
