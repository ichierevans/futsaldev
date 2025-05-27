<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegularBooking extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'day_of_week',
        'start_date',
        'end_date',
        'time_start',
        'duration',
        'duration_months',
        'status'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Get the user that owns the regular booking.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the individual bookings associated with this regular booking.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}