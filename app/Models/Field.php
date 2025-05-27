<?php
// Field.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price_per_hour',
        'status'
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}