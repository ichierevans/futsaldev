<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Booking;
use App\Models\Lapangan;

class UserController extends Controller
{
    /**
     * Display the user profile.
     *
     * @return \Illuminate\View\View
     */
    public function profile()
    {
        $user = Auth::user();
        return view('users.profile', compact('user'));
    }

    /**
     * Show the change password form.
     *
     * @return \Illuminate\View\View
     */
    public function showChangePassword()
    {
        return view('users.password');
    }

    /**
     * Update the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('user.profile')->with('success', 'Password berhasil diubah!');
    }

    /**
     * Show the field schedule.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function jadwal(Request $request)
    {
        $date = $request->input('date', date('Y-m-d'));

        $bookings = Booking::with(['user', 'field'])
            ->whereDate('tanggal', $date)
            ->where('status', 'confirmed')
            ->orderBy('jam_mulai')
            ->get();

        $fields = Lapangan::orderBy('id')->get();

        return view('users.jadwal', compact('bookings', 'fields', 'date'));
    }

    /**
     * Show the orders and history.
     *
     * @return \Illuminate\View\View
     */
    public function pesanan()
    {
        // In a real application, you would fetch orders from the database
        // For this example, we'll create some sample data
        $pendingOrders = [
            (object)[
                'id' => 1,
                'user_name' => 'Cristiano Ronaldo',
                'date_formatted' => '21-04-2025',
                'field_name' => 'Lapangan 2',
                'duration' => 2,
                'time_start' => '15.00',
                'time_end' => '17.00',
                'price_formatted' => 'Rp 300.000',
                'status' => 'Menunggu Konfirmasi'
            ]
        ];

        $completedOrders = [
            (object)[
                'id' => 2,
                'user_name' => 'Cristiano Ronaldo',
                'date_formatted' => '18-04-2025',
                'field_name' => 'Lapangan 1',
                'duration' => 2,
                'time_start' => '09.00',
                'time_end' => '11.00',
                'price_formatted' => 'Rp 300.000',
                'status' => 'Selesai'
            ],
            (object)[
                'id' => 3,
                'user_name' => 'Cristiano Ronaldo',
                'date_formatted' => '15-04-2025',
                'field_name' => 'Lapangan 3',
                'duration' => 3,
                'time_start' => '12.00',
                'time_end' => '15.00',
                'price_formatted' => 'Rp 450.000',
                'status' => 'Selesai'
            ],
            (object)[
                'id' => 4,
                'user_name' => 'Cristiano Ronaldo',
                'date_formatted' => '10-04-2025',
                'field_name' => 'Lapangan 5',
                'duration' => 2,
                'time_start' => '17.00',
                'time_end' => '19.00',
                'price_formatted' => 'Rp 350.000',
                'status' => 'Selesai'
            ]
        ];

        return view('users.pesanan', compact('pendingOrders', 'completedOrders'));
    }
}