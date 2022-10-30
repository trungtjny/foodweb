<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable =[
        'user_id',
        'shop_id',
        'name',
        'phone',
        'address',
        'message',
        'status',
        'totalprice',
    ];

    protected $cats = [
        // 'created_at'=> 'datetime:Y-m-d:H-i-s'
    ];
    CONST PENDING = 0;
    CONST PREPARE = 1;
    CONST DELIVER = 2;
    CONST COMPLETE = 3;
    CONST CANCEL = 4;
    CONST FAIL =5;
    public function orderItems()
    {
        return $this ->hasMany(OrderItem::class);
    }

    public function user()
    {
        return  $this->belongsTo(User::class,'user_id','id');
    }
    
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}
