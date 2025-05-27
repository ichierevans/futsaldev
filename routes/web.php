<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RegulerBookingController;
use App\Http\Controllers\Admin\LapanganController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// =======================
// Halaman Awal
// =======================
Route::get('/', function () {
    $lapangans = \App\Models\Lapangan::where('status', 'tersedia')->get();
    return view('welcome', compact('lapangans'));
})->name('welcome');

// =======================
// Auth: Login & Logout
// =======================
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.process');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// =======================
// Register
// =======================
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

// =======================
// User Routes (Authenticated Users Only)
// =======================
Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    // Profil & Password
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::get('/password', [UserController::class, 'showChangePassword'])->name('password');
    Route::post('/password', [UserController::class, 'updatePassword'])->name('password.update');

    // Jadwal & Pesanan
    Route::get('/jadwal', [UserController::class, 'jadwal'])->name('jadwal');
    Route::get('/pesanan', [UserController::class, 'pesanan'])->name('pesanan');

    // Booking
    Route::prefix('booking')->name('booking.')->group(function () {
        Route::get('/reguler', [BookingController::class, 'showReguler'])->name('reguler');
        Route::post('/process', [RegulerBookingController::class, 'processBooking'])->name('process');
        Route::post('/payment', [RegulerBookingController::class, 'processPayment'])->name('payment');
        Route::get('/membership', [BookingController::class, 'showMembership'])->name('membership');
        Route::get('/event', [BookingController::class, 'showEvent'])->name('event');
        Route::get('/form/{id}', [BookingController::class, 'showForm'])->name('form');
        Route::get('/create', [BookingController::class, 'create'])->name('create');
    });
});

// =======================
// Admin Routes (Authenticated + Admin Only)
// =======================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Customer Management
    Route::get('/data-customer', [CustomerController::class, 'index'])->name('data.customer');
    Route::post('/customer', [CustomerController::class, 'store'])->name('customer.store');
    Route::put('/customer/{customer}', [CustomerController::class, 'update'])->name('customer.update');
    Route::delete('/customer/{customer}', [CustomerController::class, 'destroy'])->name('customer.destroy');
    
    Route::get('/data-lapangan', [AdminController::class, 'dataLapangan'])->name('data.lapangan');
    Route::get('/transaksi', [AdminController::class, 'transaksi'])->name('transaksi');
    Route::get('/laporan', [AdminController::class, 'laporan'])->name('laporan');

    // Ganti Password
    Route::get('/ubah-password', [AdminController::class, 'passwordForm'])->name('password.form');
    Route::post('/ubah-password', [AdminController::class, 'updatePassword'])->name('password.update');

    // Konfirmasi dan Tolak Booking
    Route::post('/booking/confirm', [AdminController::class, 'confirmBooking'])->name('booking.confirm');
    Route::post('/booking/reject', [AdminController::class, 'rejectBooking'])->name('booking.reject');
    
    // New route for updating booking status
    Route::put('/booking/{bookingId}/status', [AdminController::class, 'updateBookingStatus'])
        ->name('booking.update.status');
    Route::post('/admin/pelunasan/{id}', [PelunasanController::class, 'store'])->name('admin.pelunasan.dp');
    Route::get('/admin/pelunasan', [PelunasanController::class, 'index'])->name('admin.pelunasan.index');


    // Pelunasan Routes
    Route::prefix('pelunasan')->name('pelunasan.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\PelunasanController::class, 'index'])->name('index');
        Route::get('/{id}', [App\Http\Controllers\Admin\PelunasanController::class, 'detail'])->name('detail');
        Route::post('/{id}/process', [App\Http\Controllers\Admin\PelunasanController::class, 'processSettlement'])->name('process');
        // New route for pelunasan report
        Route::get('/report', [App\Http\Controllers\Admin\PelunasanController::class, 'report'])->name('report');
        // New route for DP payment
        Route::post('/{id}/dp', [App\Http\Controllers\Admin\PelunasanController::class, 'processDpSettlement'])->name('dp');
    });
    

    // Tambahkan route baru untuk pencarian customer dan pelunasan
    Route::get('/search-customer', [AdminController::class, 'searchCustomer'])->name('search.customer');
    Route::get('/customer-bookings/{userId}', [AdminController::class, 'getCustomerBookings'])->name('customer.bookings');
    Route::post('/process-settlement/{bookingId}', [AdminController::class, 'processSettlement'])->name('process.settlement');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/pesanan', [OrderController::class, 'index'])->name('pesanan');
    Route::get('/api/orders/{type}', [OrderController::class, 'getOrdersByType']);
    Route::get('/api/confirmed-bookings', [OrderController::class, 'getConfirmedBookings'])->name('api.confirmed.bookings')
        ->middleware('web');
    
    
//     // New comprehensive debug route
//     Route::get('/debug/bookings', function() {
//         $user = Auth::user();
//         $bookings = \App\Models\Booking::where('user_id', $user->id)->get();
        
//         return response()->json([
//             'user_id' => $user->id,
//             'user_name' => $user->name,
//             'total_bookings' => $bookings->count(),
//             'bookings' => $bookings->map(function($booking) {
//                 return [
//                     'id' => $booking->id,
//                     'status' => $booking->status,
//                     'jenis_booking' => $booking->jenis_booking,
//                     'tanggal' => $booking->tanggal,
//                     'jam_mulai' => $booking->jam_mulai,
//                     'jam_selesai' => $booking->jam_selesai,
//                     'total_harga' => $booking->total_harga
//                 ];
//             })
//         ]);
//     })->name('debug.bookings');
});

Route::prefix('booking')->group(function () {
    Route::get('/', [BookingController::class, 'showBookingForm'])->name('booking.form');
    Route::post('/process', [BookingController::class, 'processBooking'])->name('booking.process');
    Route::get('/payment/{booking}', [BookingController::class, 'showPaymentForm'])->name('booking.payment');
    Route::post('/payment/{booking}', [BookingController::class, 'processPayment'])->name('booking.payment.process');
    Route::get('/success/{booking}', [BookingController::class, 'showSuccessPage'])->name('booking.success');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
});

// Admin Lapangan Routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/lapangan', [LapanganController::class, 'index'])->name('admin.data.lapangan');
    Route::post('/admin/lapangan', [LapanganController::class, 'store'])->name('admin.lapangan.store');
    Route::put('/admin/lapangan/{lapangan}', [LapanganController::class, 'update'])->name('admin.lapangan.update');
    Route::delete('/admin/lapangan/{lapangan}', [LapanganController::class, 'destroy'])->name('admin.lapangan.destroy');
});

// Route untuk update jadwal
Route::get('/update-jadwal', function(Request $request) {
    $date = $request->input('date', date('Y-m-d'));

    $bookings = \App\Models\Booking::with(['user', 'lapangan'])
        ->whereDate('tanggal', $date)
        ->where('status', 'confirmed')
        ->orderBy('jam_mulai')
        ->get();

    $lapangans = \App\Models\Lapangan::all();

    return response()->json([
        'bookings' => $bookings->map(function($booking) {
            return [
                'lapangan_id' => $booking->lapangan_id,
                'user_name' => $booking->user->name,
                'jam_mulai' => $booking->jam_mulai,
                'jam_selesai' => $booking->jam_selesai
            ];
        }),
        'lapangans' => $lapangans->pluck('nama', 'id')
    ]);
});