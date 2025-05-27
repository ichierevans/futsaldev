<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'customer_id',
        'amount',
        'payment_method',
        'payment_status',
        'payment_date',
        'notes'
    ];

    // Relation with booking
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    // Relation with customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
} 