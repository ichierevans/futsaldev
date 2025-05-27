<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\TimeSlot;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\Lapangan;
use Illuminate\Support\Facades\Schema;

class RegulerBookingController extends Controller
{
    public function showBookingForm()
    {
        // Fetch all active fields
        $fields = Lapangan::where('status', 'tersedia')->get();
        
        // Debug: Log image paths
        \Log::info('Fields Debug', [
            'fields_count' => $fields->count(),
            'fields_data' => $fields->map(function($field) {
                return [
                    'id' => $field->id,
                    'nama' => $field->nama,
                    'image' => $field->image,
                    'full_image_path' => $field->image ? asset('storage/' . $field->image) : null
                ];
            })
        ]);
        
        return view('users.booking.reguler', compact('fields'));
    }

    public function processBooking(Request $request)
    {
        \Log::info('Booking Request Data:', [
            'all_data' => $request->all(),
            'user_id' => auth()->id(),
            'is_authenticated' => auth()->check()
        ]);

        try {
            // Validasi data booking
            $validated = $request->validate([
                'lapangan_id' => 'required|integer',
                'jenis_booking' => 'required|in:reguler,membership,event',
                'tanggal' => 'required|date',
                'waktu' => 'required',
                'durasi' => 'required|integer|min:1|max:12',
            ]);

            \Log::info('Validated Booking Data:', $validated);

            // Check if the time slot is available
            $isAvailable = $this->checkTimeSlotAvailability(
                $validated['lapangan_id'],
                $validated['tanggal'],
                $validated['waktu'],
                $validated['durasi']
            );

            if (!$isAvailable) {
                \Log::warning('Time slot not available', [
                    'field_id' => $validated['lapangan_id'],
                    'date' => $validated['tanggal'],
                    'time' => $validated['waktu'],
                    'duration' => $validated['durasi']
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Jadwal sudah dipesan. Silakan pilih waktu lain.'
                ], 422);
            }

            // Calculate start and end times
            $startTime = $validated['waktu'];
            $endTime = date('H:i:s', strtotime($startTime . ' + ' . $validated['durasi'] . ' hours'));

            // Get the field price
            $field = Lapangan::findOrFail($validated['lapangan_id']);
            $hour = (int)date('H', strtotime($startTime));
            $price = ($hour >= 17) ? $field->harga_malam : $field->harga_siang;
            $totalPrice = $price * $validated['durasi'];

            // Calculate DP amount (50% of total price)
            $dpAmount = $totalPrice * 0.5;

            // Create booking
            $booking = new Booking([
                'user_id' => auth()->id(),
                'lapangan_id' => $validated['lapangan_id'],
                'tanggal' => $validated['tanggal'],
                'jam_mulai' => $startTime,
                'jam_selesai' => $endTime,
                'duration' => $validated['durasi'],
                'total_harga' => $totalPrice,
                'dp' => $dpAmount,
                'jenis_booking' => $validated['jenis_booking'],
                'status' => 'pending'
            ]);

            $booking->save();

            \Log::info('Booking Created Successfully', [
                'booking_id' => $booking->id,
                'total_price' => $totalPrice,
                'dp_amount' => $dpAmount
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Booking berhasil dibuat',
                'total_price' => 'Rp ' . number_format($totalPrice, 0, ',', '.'),
                'total_price_raw' => $totalPrice,
                'dp_amount' => 'Rp ' . number_format($dpAmount, 0, ',', '.'),
                'dp_amount_raw' => $dpAmount
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Booking Validation Error', [
                'errors' => $e->errors(),
                'input' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Data booking tidak valid',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            \Log::error('Booking Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'input' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses booking: ' . $e->getMessage()
            ], 500);
        }
    }

    public function processPayment(Request $request)
    {
        \Log::info('Payment Request Received', [
            'request_data' => $request->all(),
            'user_id' => auth()->id(),
            'is_authenticated' => auth()->check()
        ]);

        try {
            DB::beginTransaction();

            // Validate the request
            $validated = $request->validate([
                'bank' => 'required|string|in:bri,bca,dana,shopeepay,mandiri',
                'payment_proof' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Get the latest pending booking for the current user
            $booking = Booking::where('user_id', auth()->id())
                            ->where('status', 'pending')
                            ->latest()
                            ->first();

            \Log::info('Booking Found', [
                'booking_exists' => $booking !== null,
                'booking_details' => $booking ? $booking->toArray() : 'No booking found'
            ]);

            if (!$booking) {
                throw new \Exception('Data booking tidak ditemukan. Silakan ulangi proses booking.');
            }

            // Verifikasi file upload
            if (!$request->hasFile('payment_proof') || !$request->file('payment_proof')->isValid()) {
                throw new \Exception('File bukti pembayaran tidak valid.');
            }

            // Generate unique filename with user ID and timestamp
            $user = auth()->user();
            $originalFileName = $request->file('payment_proof')->getClientOriginalName();
            $extension = $request->file('payment_proof')->getClientOriginalExtension();
            $uniqueFileName = $user->id . '_' . time() . '_payment_proof.' . $extension;

            // Define storage paths
            $storagePath = 'payment_proofs/' . date('Y/m');
            
            // Store the file
            $proofPath = $request->file('payment_proof')->storeAs(
                $storagePath, 
                $uniqueFileName, 
                'public'
            );

            \Log::info('Payment Proof Uploaded', [
                'original_filename' => $originalFileName,
                'stored_filename' => $uniqueFileName,
                'storage_path' => $proofPath
            ]);

            if (!$proofPath) {
                throw new \Exception('Gagal mengunggah bukti pembayaran.');
            }

            // Delete old payment proof if exists
            if ($booking->payment_proof) {
                // Use Storage facade to delete old file
                Storage::disk('public')->delete($booking->payment_proof);
            }

            // Calculate DP amount (50% of total price)
            $dpAmount = $booking->total_harga * 0.5;
            $remainingAmount = $booking->total_harga - $dpAmount;

            // Log detailed booking information before update
            \Log::info('Payment Processing Details', [
                'booking_id' => $booking->id,
                'total_harga' => $booking->total_harga,
                'dp_amount' => $dpAmount,
                'remaining_amount' => $remainingAmount,
                'payment_bank' => $validated['bank'],
                'payment_proof_path' => $proofPath
            ]);

            // Update booking with payment information
            $booking->update([
                'payment_proof' => $proofPath, // Save only the path
                'payment_bank' => $validated['bank'],
                'payment_date' => now(),
                'dp' => $dpAmount, // Changed from 'dp_amount' to 'dp'
                'remaining_amount' => $remainingAmount,
                'payment_status' => 'partial', // Set payment status to partial
                'status' => 'waiting_confirmation'
            ]);

            // Log the update result
            \Log::info('Booking Update Result', [
                'booking_id' => $booking->id,
                'updated_booking' => $booking->fresh()->toArray()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran DP berhasil diproses',
                'dp_amount' => 'Rp ' . number_format($dpAmount, 0, ',', '.'),
                'remaining_amount' => 'Rp ' . number_format($remainingAmount, 0, ',', '.')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Payment Processing Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }

    public function processDPPayment(Request $request)
    {
        try {
            // Begin database transaction
            DB::beginTransaction();

            // Validate the request
            $validated = $request->validate([
                'booking_id' => 'required|exists:bookings,id',
                'bank' => 'required|string',
                'payment_proof' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            // Find the booking with additional checks
            $booking = Booking::findOrFail($validated['booking_id']);

            // Verify file upload
            if (!$request->hasFile('payment_proof') || !$request->file('payment_proof')->isValid()) {
                throw new \Exception('File bukti pembayaran tidak valid.');
            }

            // Generate unique filename
            $user = auth()->user();
            $extension = $request->file('payment_proof')->getClientOriginalExtension();
            $uniqueFileName = $user->id . '_' . time() . '_dp_proof.' . $extension;

            // Define storage path
            $storagePath = 'dp_payment_proofs/' . date('Y/m');
            
            // Store the file
            $proofPath = $request->file('payment_proof')->storeAs(
                $storagePath, 
                $uniqueFileName, 
                'public'
            );

            if (!$proofPath) {
                throw new \Exception('Gagal mengunggah bukti pembayaran.');
            }

            // Calculate DP amount (50% of total price)
            $dpAmount = $booking->total_price / 2;

            // Prepare update data with safe column names
            $updateData = [
                'status' => 'waiting_confirmation'
            ];

            // Dynamically add columns if they exist
            $columns = Schema::getColumnListing('bookings');
            
            if (in_array('payment_proof', $columns)) {
                $updateData['payment_proof'] = $proofPath;
            }
            
            if (in_array('payment_bank', $columns)) {
                $updateData['payment_bank'] = $validated['bank'];
            }
            
            if (in_array('payment_date', $columns)) {
                $updateData['payment_date'] = now();
            }
            
            if (in_array('dp', $columns)) {
                $updateData['dp'] = $dpAmount;
            }

            // Update booking with payment information
            $booking->update($updateData);

            // Commit the transaction
            DB::commit();

            // Log the successful payment
            \Log::info('DP Payment Processed', [
                'booking_id' => $booking->id,
                'user_id' => $user->id,
                'dp_amount' => $dpAmount,
                'bank' => $validated['bank']
            ]);

            // Return success response
            return response()->json([
                'success' => true,
                'message' => 'Pembayaran DP berhasil! Mohon tunggu konfirmasi dari admin.',
                'booking_id' => $booking->id,
                'total_price' => 'Rp ' . number_format($booking->total_price, 0, ',', '.'),
                'dp_amount' => 'Rp ' . number_format($dpAmount, 0, ',', '.'),
                'payment_proof_url' => asset('storage/' . $proofPath)
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Rollback the transaction
            DB::rollback();

            // Log validation errors
            \Log::error('DP Payment Validation Error', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);

            // Return validation error response
            return response()->json([
                'success' => false,
                'message' => 'Data yang dimasukkan tidak valid.',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            // Rollback the transaction
            DB::rollback();

            // Log the error
            \Log::error('DP Payment Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            // Return error response
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function checkTimeSlotAvailability($fieldId, $date, $startTime, $duration)
    {
        try {
            $startDateTime = Carbon::parse($date . ' ' . $startTime);
            $endDateTime = $startDateTime->copy()->addHours($duration);

            $overlappingBookings = Booking::where('lapangan_id', $fieldId)
                ->where('tanggal', $date)
                ->where(function ($query) use ($startDateTime, $endDateTime) {
                    $query->whereBetween('jam_mulai', [$startDateTime->format('H:i:s'), $endDateTime->format('H:i:s')])
                        ->orWhereBetween('jam_selesai', [$startDateTime->format('H:i:s'), $endDateTime->format('H:i:s')])
                        ->orWhere(function ($q) use ($startDateTime, $endDateTime) {
                            $q->where('jam_mulai', '<=', $startDateTime->format('H:i:s'))
                                ->where('jam_selesai', '>=', $endDateTime->format('H:i:s'));
                        });
                })
                ->where('status', '!=', 'cancelled')
                ->exists();

            return !$overlappingBookings;
        } catch (\Exception $e) {
            Log::error('Error checking time slot availability: ' . $e->getMessage());
            throw $e;
        }
    }

    private function getPricePerHour($jenisBooking)
    {
        $prices = [
            'reguler' => 100000,
            'membership' => 80000, // Diskon 20%
            'event' => 90000, // Diskon 10%
        ];

        Log::info('Getting price per hour', [
            'jenisBooking' => $jenisBooking,
            'price' => $prices[$jenisBooking] ?? 100000
        ]);

        return $prices[$jenisBooking] ?? 100000;
    }
}