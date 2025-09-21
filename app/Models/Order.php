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

    protected $casts = [
        'order_date' => 'datetime',
        'due_date' => 'datetime',
        'per_bag_weight' => 'array',
        'rate' => 'decimal:2',
        'quantity' => 'integer',
        'discounted_bag_weight' => 'decimal:2',
        'total_weight' => 'decimal:2',
        'packaging_charge' => 'decimal:2',
        'hamali_charge' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'grand_amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class)->withTrashed();
    }
    
    /**
     * Check if the order belongs to the authenticated user
     */
    public function belongsToUser($userId)
    {
        return $this->user_id === $userId;
    }
}
