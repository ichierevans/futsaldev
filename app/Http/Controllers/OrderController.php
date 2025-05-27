<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index()
    {
        return view('users.pesanan');
    }

    public function getOrdersByType($type)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'error' => 'Autentikasi gagal',
                    'message' => 'Pengguna tidak terautentikasi'
                ], 401);
            }

            // Validate booking type
            $validTypes = ['reguler', 'membership', 'event'];
            if (!in_array($type, $validTypes)) {
                return response()->json([
                    'error' => 'Tipe booking tidak valid',
                    'message' => "Tipe booking '$type' tidak dikenali"
                ], 400);
            }

            // Current date for filtering
            $currentDate = now()->toDateString();

            // Fetch pending orders (waiting for confirmation)
            $pendingOrders = Booking::with(['field'])
                ->where('user_id', $user->id)
                ->whereIn('status', ['pending', 'waiting_confirmation'])
                ->where('jenis_booking', $type)
                ->orderBy('created_at', 'desc')
                ->get();

            // Fetch confirmed and active orders (not yet completed)
            $confirmedOrders = Booking::with(['field'])
                ->where('user_id', $user->id)
                ->whereIn('status', ['confirmed', 'booking_confirmed'])
                ->where('jenis_booking', $type)
                ->whereDate('tanggal', '>=', $currentDate)
                ->orderBy('created_at', 'desc')
                ->get();

            // Fetch completed orders (past confirmed orders)
            $completedOrders = Booking::with(['field'])
                ->where('user_id', $user->id)
                ->whereIn('status', ['confirmed', 'booking_confirmed', 'completed'])
                ->where('jenis_booking', $type)
                ->whereDate('tanggal', '<', $currentDate)
                ->orderBy('created_at', 'desc')
                ->get();

            // Fetch cancelled orders
            $cancelledOrders = Booking::with(['field'])
                ->where('user_id', $user->id)
                ->where('status', 'cancelled')
                ->where('jenis_booking', $type)
                ->orderBy('created_at', 'desc')
                ->get();

            // Map function for consistent response format
            $mapOrder = function ($booking) use ($user) {
                return [
                    'id' => $booking->id,
                    'user_name' => $user->name,
                    'booking_date' => $booking->tanggal,
                    'field_name' => $booking->field->nama ?? 'Lapangan ' . $booking->lapangan_id,
                    'duration' => $this->calculateDuration($booking->jam_mulai, $booking->jam_selesai),
                    'start_time' => $booking->jam_mulai,
                    'end_time' => $booking->jam_selesai,
                    'total_price' => floatval($booking->total_harga),
                    'status' => $booking->status,
                    'jenis_booking' => $booking->jenis_booking
                ];
            };

            return response()->json([
                'pendingOrders' => $pendingOrders->map($mapOrder),
                'confirmedOrders' => $confirmedOrders->map($mapOrder),
                'completedOrders' => $completedOrders->map($mapOrder),
                'cancelledOrders' => $cancelledOrders->map($mapOrder)
            ]);

        } catch (\Exception $e) {
            Log::error('Order retrieval error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'type' => $type,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Gagal memuat data',
                'message' => 'Terjadi kesalahan saat mengambil data pesanan'
            ], 500);
        }
    }

    private function calculateDuration($startTime, $endTime)
    {
        try {
            $start = new \DateTime($startTime);
            $end = new \DateTime($endTime);
            $diff = $start->diff($end);
            return $diff->h;
        } catch (\Exception $e) {
            return 0;
        }
    }

    // Debug endpoint
    public function debugOrderFetching($type = null)
    {
        $query = Booking::with(['field'])
            ->where('user_id', Auth::id());
        
        if ($type) {
            $query->where('jenis_booking', $type);
        }

        $bookings = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'user_id' => Auth::id(),
            'type_filter' => $type,
            'total_bookings' => $bookings->count(),
            'bookings' => $bookings->map(function($booking) {
                return [
                    'id' => $booking->id,
                    'status' => $booking->status,
                    'jenis_booking' => $booking->jenis_booking,
                    'tanggal' => $booking->tanggal,
                    'jam_mulai' => $booking->jam_mulai,
                    'jam_selesai' => $booking->jam_selesai,
                    'total_harga' => $booking->total_harga,
                    'field_name' => $booking->field->nama ?? null,
                    'is_past' => now()->toDateString() > $booking->tanggal ? 'Ya' : 'Tidak'
                ];
            })
        ]);
    }
}