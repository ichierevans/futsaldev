<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        // tambahkan kolom lain yang diperlukan
    ];

    // Relation dengan booking (jika diperlukan)
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}