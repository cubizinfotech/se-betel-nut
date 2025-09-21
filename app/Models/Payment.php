<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'payments';

    protected $fillable = [
        'user_id',
        'customer_id',
        'trans_number',
        'payment_method',
        'amount',
        'payment_date',
        'payment_time',
    ];

    protected $casts = [
        'payment_date' => 'date',
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
    
    public function order()
    {
        return $this->belongsTo(Order::class)->withTrashed();
    }
    
    /**
     * Check if the payment belongs to the authenticated user
     */
    public function belongsToUser($userId)
    {
        return $this->user_id === $userId;
    }
}
