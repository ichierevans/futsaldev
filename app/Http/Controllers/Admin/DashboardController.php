<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Lapangan;
use App\Models\Transaction;
use App\Models\Booking;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $data = [
            'customerCount' => User::where('email', 'NOT LIKE', '%admin%')->count(),
            'fieldCount' => Lapangan::count(),
            'bookingCount' => Booking::count(),
        ];

        // Fetch recent transactions
        $data['recentTransactions'] = Transaction::with(['booking', 'customer'])
            ->latest()
            ->take(5)
            ->get();

        // Check if payment_status column exists
        if (Schema::hasColumn('bookings', 'payment_status')) {
            $data['pelunasanCount'] = Booking::whereIn('payment_status', ['pending', 'partial'])->count();
            $data['pelunasanList'] = Booking::with(['user', 'lapangan'])
                ->whereIn('payment_status', ['pending', 'partial'])
                ->latest()
                ->take(5)
                ->get();
        } else {
            // Fallback if column doesn't exist
            $data['pelunasanCount'] = 0;
            $data['pelunasanList'] = collect();
        }

        return view('admin.dashboard', $data);
    }
} 