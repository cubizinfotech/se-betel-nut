<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'customer_id',
        'order_number',
        'product_name',
        'lot_number',
        'rate',
        'quantity',
        'discounted_bag_weight',
        'per_bag_weight',
        'total_weight',
        'packaging_charge',
        'hamali_charge',
        'order_date',
        'due_date',
        'total_amount',
        'grand_amount',
    ];

    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
