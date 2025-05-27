<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pelunasan;
use App\Models\Booking;
use Illuminate\Support\Facades\Storage;

class PelunasanController extends Controller
{   public function index()
    {
        $pelunasanList = Booking::with(['user', 'lapangan', 'pelunasan'])
            ->whereNotNull('payment_proof')
            ->where('status', '!=', 'cancelled')
            ->latest()
            ->get()
            ->map(function ($booking) {
                // Calculate DP (Down Payment)
                $booking->jumlah_dp = $booking->total_harga - ($booking->remaining_amount ?? $booking->total_harga);
                return $booking;
            });

        return view('admin.pelunasan.index', compact('pelunasanList'));
    }
    public function store(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        // Validate the request
        $request->validate([
            'payment_method' => 'nullable|string|max:50',
            'payment_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048'
        ]);

        // Calculate DP amount (50% of total)
        $dpAmount = $booking->total_harga / 2;

        // Handle payment proof upload
        $paymentProofPath = null;
        
        // Determine manual input based on payment method and proof
        $isManualInput = 0;
        if (!$request->payment_method && !$request->hasFile('payment_proof')) {
            $isManualInput = 1;
        }

        if ($request->hasFile('payment_proof')) {
            $file = $request->file('payment_proof');
            $filename = time() . '_' . $file->getClientOriginalName();
            $paymentProofPath = $file->storeAs('public/dp_payments', $filename);
        }

        // Create Pelunasan record for DP
        $pelunasan = new Pelunasan();
        $pelunasan->booking_id = $booking->id;
        $pelunasan->payment_amount = $dpAmount;
        $pelunasan->payment_method = $request->payment_method;
        $pelunasan->payment_proof = $paymentProofPath ? basename($paymentProofPath) : null;
        $pelunasan->payment_status = 'partial';
        $pelunasan->paid_at = now();
        $pelunasan->save();

        // Update booking status
        $booking->payment_status = 'partial';
        $booking->remaining_amount = $booking->total_harga - $dpAmount;
        $booking->save();

        // Prepare success message with payment details
        $successMessage = "Pembayaran DP berhasil diproses. 
            Jumlah: Rp. " . number_format($dpAmount, 0, ',', '.') . " 
            Metode: " . ($request->payment_method ?? 'Tidak ada') . " 
            Status: Parsial";

        return redirect()->back()->with('success', $successMessage);
    }

    public function detail($id)
    {
        $booking = Booking::with(['user', 'lapangan', 'pelunasan'])
            ->findOrFail($id);

        return view('admin.pelunasan.detail', compact('booking'));
    }

    public function processDpSettlement(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        // Validate the request
        $request->validate([
            'payment_amount' => 'required|numeric|min:1|max:' . $booking->remaining_amount,
            'payment_method' => 'nullable|string|max:50',
            'payment_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048'
        ]);

        // Handle payment proof upload
        $paymentProofPath = null;
        
        // Determine manual input based on payment method and proof
        $isManualInput = 0;
        if (!$request->payment_method && !$request->hasFile('payment_proof')) {
            $isManualInput = 1;
        }

        if ($request->hasFile('payment_proof')) {
            $file = $request->file('payment_proof');
            $filename = time() . '_' . $file->getClientOriginalName();
            $paymentProofPath = $file->storeAs('public/dp_payments', $filename);
        }

        // Create Pelunasan record
        $pelunasan = new Pelunasan();
        $pelunasan->booking_id = $booking->id;
        $pelunasan->payment_amount = $request->payment_amount;
        $pelunasan->payment_method = $request->payment_method;
        $pelunasan->payment_proof = $paymentProofPath ? basename($paymentProofPath) : null;
        
        // Determine payment status
        if ($request->payment_amount >= $booking->remaining_amount) {
            $pelunasan->payment_status = 'completed';
        } else {
            $pelunasan->payment_status = 'partial';
        }

        
        
        $pelunasan->paid_at = now();
        $pelunasan->save();

        // Update booking status
        $booking->payment_status = $pelunasan->payment_status;
        $booking->remaining_amount = max(0, $booking->remaining_amount - $request->payment_amount);
        $booking->save();

        // Prepare success message with payment details
        $successMessage = "Pembayaran berhasil diproses. " .
            "Jumlah: Rp. " . number_format($request->payment_amount, 0, ',', '.') . " " .
            "Metode: " . ($request->payment_method ?? 'Tidak ada') . " " .
            "Status: " . ucfirst($pelunasan->payment_status);

        // Redirect to index with success message
        return redirect()->route('admin.pelunasan.index')->with('success', $successMessage);
    }

    public function report(Request $request)
    {
        $pelunasanList = Booking::with(['user', 'lapangan', 'pelunasan'])
            ->whereHas('pelunasan')
            ->latest()
            ->get();

        return view('admin.pelunasan.report', compact('reportList', 'pelunasanList'));
    }
}
