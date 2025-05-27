<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Schema;

class Booking extends Model
{
    use HasFactory;

    // Valid booking statuses
    const VALID_STATUSES = ['pending', 'waiting_confirmation', 'confirmed', 'cancelled'];

    // Valid payment statuses
    const VALID_PAYMENT_STATUSES = ['pending', 'partial', 'completed'];

    // Update fillable attributes to include new columns
    protected $fillable = [
        'user_id',
        'lapangan_id',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'total_harga',
        'status',
        'jenis_booking',
        'payment_proof',
        'payment_bank',
        'payment_date',
        'payment_status',
        'dp_amount',
        'dp_payment_method',
        'dp_payment_proof',
        'dp_payment_date',
        'remaining_amount'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jam_mulai' => 'datetime:H:i:s',
        'jam_selesai' => 'datetime:H:i:s',
        'total_harga' => 'decimal:2',
        'dp_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'jenis_booking' => 'string',
        'payment_date' => 'datetime',
        'dp_payment_date' => 'datetime',
        'payment_status' => 'string'
    ];

    /**
     * Get the start time.
     */
    protected function jamMulai(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? date('H:i:s', strtotime($value)) : null,
            set: fn ($value) => $value ? date('H:i:s', strtotime($value)) : null
        );
    }

    /**
     * Get the end time.
     */
    protected function jamSelesai(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? date('H:i:s', strtotime($value)) : null,
            set: fn ($value) => $value ? date('H:i:s', strtotime($value)) : null
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function field()
    {
        return $this->belongsTo(Lapangan::class, 'lapangan_id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Mutator for status to ensure only valid statuses are set
    public function setStatusAttribute($value)
    {
        if (!in_array($value, self::VALID_STATUSES)) {
            throw new \InvalidArgumentException("Invalid booking status: $value");
        }
        $this->attributes['status'] = $value;
    }

    // Ensure payment_status column exists before setting
    public function setAttribute($key, $value)
    {
        // Check if the column exists before setting
        if ($key === 'payment_status' && !Schema::hasColumn($this->getTable(), 'payment_status')) {
            // Optionally log or handle the missing column
            return $this;
        }

        return parent::setAttribute($key, $value);
    }

    // Scope to handle missing payment_status column
    public function scopePaymentPending($query)
    {
        // Check if payment_status column exists
        if (Schema::hasColumn($this->getTable(), 'payment_status')) {
            return $query->where('payment_status', 'pending');
        }
        
        // Fallback to default status if column doesn't exist
        return $query->where('status', 'pending');
    }

    // Pelunasan-related methods
    public function setPaymentStatusAttribute($value)
    {
        // Only set if column exists
        if (Schema::hasColumn($this->getTable(), 'payment_status')) {
            if (!in_array($value, self::VALID_PAYMENT_STATUSES)) {
                throw new \InvalidArgumentException("Invalid payment status: $value");
            }
            $this->attributes['payment_status'] = $value;
        }
    }

    public function processPaymentSettlement($amount)
    {
        // Check if payment_status column exists
        if (!Schema::hasColumn($this->getTable(), 'payment_status')) {
            throw new \RuntimeException("Payment status column does not exist");
        }

        // Validate payment amount
        if ($amount > $this->remaining_amount) {
            throw new \InvalidArgumentException("Payment amount exceeds remaining balance");
        }

        // Update remaining amount
        $this->remaining_amount -= $amount;

        // Update payment status
        if ($this->remaining_amount <= 0) {
            $this->payment_status = 'completed';
        } elseif ($amount > 0) {
            $this->payment_status = 'partial';
        }

        $this->save();

        return $this;
    }

    // Relationship with Lapangan (Field)
    public function lapangan()
    {
        return $this->belongsTo(Lapangan::class, 'lapangan_id');
    }
    public function pelunasan()
    {
    return $this->hasOne(Pelunasan::class);
    }

}