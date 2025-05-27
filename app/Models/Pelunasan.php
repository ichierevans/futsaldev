<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelunasan extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'payment_amount',
        'payment_method',
        'payment_proof',
        'is_manual_input',
        'paid_at',
        'payment_status',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
