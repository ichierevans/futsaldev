<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lapangan;
use App\Models\MembershipPackage;
use App\Http\Controllers\Booking;
use App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    /**
     * Halaman booking reguler.
     */
    public function showReguler()
    {
        $fields = Lapangan::all();  // Ambil semua lapangan
        return view('users.booking.reguler', compact('fields'));
    }

    /**
     * Halaman form booking (pilih field dan slot waktu).
     */
    public function showForm($id)
    {
        $field = Lapangan::findOrFail($id);  // Ambil lapangan berdasarkan ID
        $daysOfWeek = [
            'monday' => 'Senin',
            'tuesday' => 'Selasa',
            'wednesday' => 'Rabu',
            'thursday' => 'Kamis',
            'friday' => 'Jumat',
            'saturday' => 'Sabtu',
            'sunday' => 'Minggu',
        ];

        return view('users.booking.form', compact('field', 'daysOfWeek'));
    }

    /**
     * Halaman booking membership.
     */
    public function showMembership()
    {
        $fields = Lapangan::all();
        $packages = MembershipPackage::where('is_active', true)->get();

        return view('users.booking.membership', compact('fields', 'packages'));
    }

    /**
     * Halaman booking event.
     */
    public function showEvent()
    {
        $fields = Lapangan::all();
        return view('users.booking.event', compact('fields'));
    }

    /**
     * Advanced pricing calculation with multiple factors
     * 
     * @param string $bookingType Type of booking (regular, membership, event)
     * @param string $startTime Start time of booking
     * @param string $date Date of booking
     * @param int $duration Booking duration in hours
     * @return float Calculated price
     */
    private function calculatePriceWithDynamicRates($bookingType, $startTime, $date = null, $duration = 1) 
    {
        // Base pricing structure
        $pricingRates = [
            'day' => [
                'base' => 100000,   // Day rate base price
                'peak' => 120000,   // Peak day hours
            ],
            'night' => [
                'base' => 150000,   // Night rate base price
                'peak' => 180000,   // Peak night hours
            ],
            'weekend' => [
                'base' => 130000,   // Weekend base rate
                'peak' => 160000,   // Weekend peak rate
            ]
        ];

        // Discount rates for different booking types
        $discountRates = [
            'regular' => 1.0,     // No discount
            'membership' => 0.8,  // 20% off
            'event' => 0.9,       // 10% off
        ];

        // Convert start time to hour
        $hour = intval(date('H', strtotime($startTime)));
        
        // Determine day or night pricing
        $timeCategory = ($hour >= 17 && $hour <= 22) ? 'night' : 'day';

        // Check for weekend (if date is provided)
        $isWeekend = false;
        if ($date) {
            $dayOfWeek = date('l', strtotime($date));
            $isWeekend = in_array($dayOfWeek, ['Saturday', 'Sunday']);
        }

        // Select appropriate base price
        if ($isWeekend) {
            $basePrice = $pricingRates['weekend']['base'];
        } else {
            $basePrice = $pricingRates[$timeCategory]['base'];
        }

        // Apply peak hour multiplier
        $isPeakHour = (
            ($timeCategory === 'day' && ($hour >= 12 && $hour <= 14)) || 
            ($timeCategory === 'night' && ($hour >= 19 && $hour <= 21))
        );
        
        if ($isPeakHour) {
            $basePrice = $pricingRates[$timeCategory]['peak'];
        }

        // Apply booking type discount
        $bookingType = strtolower($bookingType ?? 'regular');
        $discountRate = $discountRates[$bookingType] ?? 1.0;

        // Calculate final price with duration
        $finalPrice = $basePrice * $discountRate * $duration;

        // Additional volume discount for longer bookings
        if ($duration > 2) {
            $finalPrice *= 0.95;  // 5% off for bookings longer than 2 hours
        }

        return $finalPrice;
    }

    /**
     * Store a new booking with enhanced pricing logic
     */
    public function store(Request $request) 
    {
        $validated = $request->validate([
            'lapangan_id' => 'required|exists:lapangan,id',
            'tanggal' => 'required|date|after_or_equal:today',
            'waktu' => 'required|date_format:H:i',
            'durasi' => 'required|integer|min:1|max:12'
        ]);

        // Calculate price using the new dynamic pricing method
        $totalPrice = $this->calculatePriceWithDynamicRates(
            $request->jenis_booking ?? 'regular', 
            $validated['waktu'], 
            $validated['tanggal'], 
            $validated['durasi']
        );

        // Check for booking conflicts
        $conflictingBooking = Booking::where('lapangan_id', $validated['lapangan_id'])
            ->where('tanggal', $validated['tanggal'])
            ->where(function($query) use ($validated) {
                $startTime = $validated['waktu'];
                $endTime = date('H:i', strtotime($startTime) + ($validated['durasi'] * 3600));
                
                $query->where(function($q) use ($startTime, $endTime) {
                    $q->whereBetween('jam_mulai', [$startTime, $endTime])
                      ->orWhereBetween('jam_selesai', [$startTime, $endTime]);
                })
                ->orWhere(function($q) use ($startTime, $endTime) {
                    $q->where('jam_mulai', '<=', $startTime)
                      ->where('jam_selesai', '>=', $endTime);
                });
            })
            ->whereIn('status', ['pending', 'confirmed'])
            ->first();

        if ($conflictingBooking) {
            return back()->withErrors([
                'waktu' => 'The selected time slot is already booked. Please choose another time.'
            ])->withInput();
        }

        try {
            // Simpan booking
            $booking = Booking::create([
                'user_id' => auth()->id(),
                'lapangan_id' => $validated['lapangan_id'],
                'tanggal' => $validated['tanggal'],
                'jam_mulai' => $validated['waktu'],
                'jam_selesai' => date('H:i', strtotime($validated['waktu']) + ($validated['durasi'] * 3600)),
                'durasi' => $validated['durasi'],
                'total_harga' => $totalPrice,
                'status' => 'pending',
                'booking_type' => $request->jenis_booking ?? 'regular'
            ]);

            // Log booking creation
            Log::info('Booking created', [
                'booking_id' => $booking->id,
                'user_id' => $booking->user_id,
                'lapangan_id' => $booking->lapangan_id
            ]);

            return redirect()->route('booking.payment', $booking)
                ->with('success', 'Booking created successfully. Total price: Rp ' . number_format($totalPrice, 0, ',', '.'));
        } catch (\Exception $e) {
            Log::error('Booking creation failed', [
                'error' => $e->getMessage(),
                'input' => $request->all()
            ]);

            return back()->withErrors([
                'booking' => 'Failed to create booking. Please try again.'
            ])->withInput();
        }
    }

    /**
     * Show payment form for a booking
     */
    public function showPaymentForm(Booking $booking)
    {
        // Retrieve bank accounts from configuration
        $bankAccounts = config('futzone.bank_accounts', []);

        return view('booking.payment', [
            'booking' => $booking,
            'bankAccounts' => $bankAccounts
        ]);
    }

    /**
     * Process payment for a booking
     */
    public function processPayment(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'bank_name' => 'required|string',
            'payment_proof' => 'required|image|max:2048'
        ]);

        // Upload payment proof
        $paymentProofPath = $request->file('payment_proof')->store('payment_proofs', 'public');

        // Update booking with payment information
        $booking->update([
            'payment_bank' => $validated['bank_name'],
            'payment_proof' => $paymentProofPath,
            'status' => 'paid'
        ]);

        return redirect()->route('booking.success', $booking)
            ->with('success', 'Pembayaran berhasil diproses');
    }
}
