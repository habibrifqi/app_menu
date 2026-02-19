<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_name',
        'table_no',
        'order_date',
        'ordertime',
        'status',
        'total',
        'waiters_id',
        'cashiers_id',
    ];

    protected $casts = [
        'order_date' => 'date',
        'total' => 'integer',
    ];

    public function waiter()
    {
        return $this->belongsTo(User::class, 'waiters_id');
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashiers_id');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
}
