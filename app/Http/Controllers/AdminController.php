<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Customer;
use App\Models\Field;
use App\Models\Booking;
use App\Models\User;

class AdminController extends Controller
{
    // Dashboard Admin
    public function dashboard()
    {
        $customerCount = Customer::count();
        $fieldCount = Field::count();
        $bookingCount = Booking::count();

        return view('admin.dashboard', compact('customerCount', 'fieldCount', 'bookingCount'));
    }

    // Data Customer
    public function dataCustomer()
    {
        $customers = Customer::all();
        return view('admin.data_customer', compact('customers'));
    }

    // Data Lapangan
    public function dataLapangan()
    {
        $fields = Field::all();
        return view('admin.data_lapangan', compact('fields'));
    }

    // Transaksi
    public function transaksi()
    {
        $bookings = Booking::with(['customer', 'field'])->get();
        return view('admin.transaksi', compact('bookings'));
    }

    // Laporan
    public function laporan(Request $request)
    {
        // Get date range from request, default to empty if not provided
        $startDate = $request->input('dari');
        $endDate = $request->input('sampai');

        // Query confirmed bookings
        $query = Booking::where('status', 'confirmed')
            ->with(['customer', 'field']);

        // Apply date range filter if both start and end dates are provided
        if ($startDate && $endDate) {
            $query->whereBetween('tanggal', [$startDate, $endDate]);
        }

        // Get the filtered bookings
        $bookings = $query->get();

        return view('admin.laporan', compact('bookings', 'startDate', 'endDate'));
    }

    // Form Ubah Password
    public function passwordForm()
    {
        return view('admin.ubah_password');
    }

    // Update Password
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        /** @var User $user */
        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama tidak sesuai']);
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('success', 'Password berhasil diubah');
    }

    // Konfirmasi Booking
    public function confirmBooking(Request $request)
    {
        \Log::info('Confirm Booking Request', [
            'request_data' => $request->all(),
            'user_id' => auth()->id(),
            'ip_address' => $request->ip()
        ]);

        try {
            // More comprehensive validation
            $validated = $request->validate([
                'booking_id' => [
                    'required', 
                    'integer', 
                    'exists:bookings,id'
                ]
            ]);

            // Find the booking with additional checks
            $booking = Booking::where('id', $validated['booking_id'])
                ->where('status', 'pending')
                ->firstOrFail();
            
            // Additional validation
            if (!$booking->payment_proof) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bukti pembayaran belum diunggah'
                ], 400);
            }

            // Update booking status
            $booking->status = 'confirmed';
            $booking->save();

            // Log the status change
            \Log::info('Booking Confirmed', [
                'booking_id' => $booking->id,
                'admin_id' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Booking berhasil dikonfirmasi',
                'booking_id' => $booking->id,
                'new_status' => $booking->status
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors
            \Log::error('Confirm Booking Validation Error', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . implode(', ', array_flatten($e->errors()))
            ], 422);
        } catch (\Exception $e) {
            // Log the full error
            \Log::error('Confirm Booking Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengkonfirmasi booking: ' . $e->getMessage()
            ], 500);
        }
    }

    // Tolak Booking
    public function rejectBooking(Request $request)
    {
        \Log::info('Reject Booking Request', [
            'request_data' => $request->all(),
            'user_id' => auth()->id(),
            'ip_address' => $request->ip()
        ]);

        try {
            // More comprehensive validation
            $validated = $request->validate([
                'booking_id' => [
                    'required', 
                    'integer', 
                    'exists:bookings,id'
                ]
            ]);

            // Find the booking with additional checks
            $booking = Booking::where('id', $validated['booking_id'])
                ->where('status', 'pending')
                ->firstOrFail();

            // Update booking status
            $booking->status = 'cancelled';
            $booking->save();

            // Log the status change
            \Log::info('Booking Rejected', [
                'booking_id' => $booking->id,
                'admin_id' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Booking berhasil ditolak',
                'booking_id' => $booking->id,
                'new_status' => $booking->status
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors
            \Log::error('Reject Booking Validation Error', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . implode(', ', array_flatten($e->errors()))
            ], 422);
        } catch (\Exception $e) {
            // Log the full error
            \Log::error('Reject Booking Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menolak booking: ' . $e->getMessage()
            ], 500);
        }
    }

    // Update Booking Status
    public function updateBookingStatus(Request $request, $bookingId)
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'status' => [
                    'required', 
                    'in:pending,confirmed,cancelled'
                ]
            ]);

            // Find the booking
            $booking = Booking::findOrFail($bookingId);

            // Update booking status
            $booking->status = $validated['status'];
            $booking->save();

            // Log the status change with more details
            \Log::info('Booking Status Updated', [
                'booking_id' => $booking->id,
                'old_status' => $booking->getOriginal('status'),
                'new_status' => $booking->status,
                'status_length' => strlen($booking->status),
                'admin_id' => auth()->id()
            ]);

            // Redirect back with success message
            return redirect()->route('admin.transaksi')
                ->with('success', "Status booking #$bookingId berhasil diubah menjadi {$booking->status}");
        } catch (\Exception $e) {
            // Log the full error
            \Log::error('Update Booking Status Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            // Redirect back with error message
            return redirect()->back()
                ->withErrors(['error' => 'Gagal mengubah status booking: ' . $e->getMessage()])
                ->withInput();
        }
    }

    // Pencarian Customer
    public function searchCustomer(Request $request)
    {
        $query = $request->input('query');

        $customers = User::where('email', 'NOT LIKE', '%admin%')
            ->where(function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('email', 'LIKE', "%{$query}%")
                  ->orWhere('phone', 'LIKE', "%{$query}%");
            })
            ->get();

        return response()->json($customers);
    }

    // Dapatkan Booking Customer
    public function getCustomerBookings($userId)
    {
        $bookings = Booking::with(['lapangan'])
            ->where('user_id', $userId)
            ->whereIn('payment_status', ['pending', 'partial'])
            ->get()
            ->map(function($booking) {
                return [
                    'id' => $booking->id,
                    'field' => [
                        'name' => $booking->lapangan->name
                    ],
                    'tanggal' => $booking->tanggal->format('d M Y'),
                    'total_harga' => $booking->total_harga,
                    'remaining_amount' => $booking->remaining_amount ?? 0
                ];
            });

        return response()->json($bookings);
    }

    // Proses Pelunasan
    public function processSettlement(Request $request, $bookingId)
    {
        try {
            $booking = Booking::findOrFail($bookingId);
            
            $validatedData = $request->validate([
                'payment_amount' => 'required|numeric|min:0',
                'payment_method' => 'required|string',
                'payment_proof' => 'nullable|file|max:5120' // Maksimal 5MB
            ]);
            
            // Proses pembayaran
            $paymentAmount = $validatedData['payment_amount'];
            
            // Simpan bukti pembayaran jika ada
            if ($request->hasFile('payment_proof')) {
                $file = $request->file('payment_proof');
                $filename = time() . '_' . $booking->id . '_settlement_proof.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('payment_proofs', $filename, 'public');
                
                $booking->payment_proof = $path;
            }
            
            // Update sisa pembayaran
            $booking->remaining_amount -= $paymentAmount;
            
            // Update status pembayaran
            if ($booking->remaining_amount <= 0) {
                $booking->payment_status = 'completed';
            } else {
                $booking->payment_status = 'partial';
            }
            
            $booking->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Pelunasan berhasil diproses',
                'remaining_amount' => $booking->remaining_amount,
                'payment_status' => $booking->payment_status
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses pelunasan: ' . $e->getMessage()
            ], 500);
        }
    }
}