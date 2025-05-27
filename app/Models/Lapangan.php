<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lapangan extends Model
{
    use HasFactory;

    protected $table = 'lapangan';

    protected $fillable = [
        'nama',
        'image',
        'deskripsi',
        'harga_siang',
        'harga_malam',
        'status'
    ];

    // Relation with bookings
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
} 