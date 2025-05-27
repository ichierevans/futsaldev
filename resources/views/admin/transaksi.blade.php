<!-- resources/views/admin/transaksi.blade.php -->
@extends('layouts.admin')

@section('title', 'Validasi')

@section('content')
    <h2>Validasi</h2>
    <div class="card">
        <div class="card-body">
            <!-- Transaksi table will go here -->
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Customer</th>
                        <th>Lapangan</th>
                        <th>Tanggal</th>
                        <th>Waktu</th>
                        <th>Status</th>
                        <th>Bukti Pembayaran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bookings as $booking)
                    <tr>
                        <td>{{ $booking->id }}</td>
                        <td>{{ $booking->customer?->name ?? 'N/A' }}</td>
                        <td>{{ $booking->field?->nama ?? 'N/A' }}</td>
                        <td>{{ $booking->tanggal ? $booking->tanggal->format('d-m-Y') : 'N/A' }}</td>
                        <td>
                            {{ $booking->jam_mulai ? \Carbon\Carbon::parse($booking->jam_mulai)->format('H:i') : 'N/A' }} - 
                            {{ $booking->jam_selesai ? \Carbon\Carbon::parse($booking->jam_selesai)->format('H:i') : 'N/A' }}
                        </td>
                        <td>
                            <span class="badge bg-{{ $booking->status == 'pending' ? 'warning' : ($booking->status == 'confirmed' ? 'success' : 'danger') }}">
                                {{ ucfirst($booking->status ?? 'N/A') }}
                            </span>
                        </td>
                        <td>
                            @if($booking->payment_proof)
                                <a href="#" class="btn btn-sm btn-info" data-bs-toggle="modal" 
                                   data-bs-target="#paymentProofModal{{ $booking->id }}">
                                    Lihat Bukti
                                </a>
                            @else
                                Belum Ada
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" 
                                    data-bs-target="#editBookingStatusModal{{ $booking->id }}">
                                Edit Status
                            </button>
                        </td>
                    </tr>

                    <!-- Edit Booking Status Modal -->
                    <div class="modal fade" id="editBookingStatusModal{{ $booking->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Status Booking #{{ $booking->id }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('admin.booking.update.status', $booking->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="status{{ $booking->id }}" class="form-label">Status Booking</label>
                                            <select class="form-select" id="status{{ $booking->id }}" name="status" required>
                                                <option value="pending" {{ $booking->status === 'pending' ? 'selected' : '' }}>
                                                    Pending
                                                </option>
                                                <option value="confirmed" {{ $booking->status === 'confirmed' ? 'selected' : '' }}>
                                                    Confirmed
                                                </option>
                                                <option value="cancelled" {{ $booking->status === 'cancelled' ? 'selected' : '' }}>
                                                    Cancelled
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Proof Modal -->
                    @if($booking->payment_proof)
                    <div class="modal fade" id="paymentProofModal{{ $booking->id }}" tabindex="-1" aria-labelledby="paymentProofModalLabel{{ $booking->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="paymentProofModalLabel{{ $booking->id }}">Bukti Pembayaran Booking #{{ $booking->id }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body text-center">
                                    <img src="{{ asset('storage/' . $booking->payment_proof) }}" 
                                         alt="Bukti Pembayaran" 
                                         class="img-fluid" 
                                         style="max-width: 100%; max-height: 500px; object-fit: contain;">
                                    <div class="mt-3">
                                        <strong>Bank:</strong> {{ $booking->payment_bank ?? 'Tidak Tersedia' }}<br>
                                        <strong>Tanggal Pembayaran:</strong> {{ $booking->payment_date ? \Carbon\Carbon::parse($booking->payment_date)->format('d-m-Y H:i:s') : 'Tidak Tersedia' }}
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <a href="{{ asset('storage/' . $booking->payment_proof) }}" 
                                       target="_blank" 
                                       class="btn btn-primary">
                                        Buka Gambar Terpisah
                                    </a>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Show validation errors in modals if any
    @if($errors->any())
        var errorMessages = '';
        @foreach($errors->all() as $error)
            errorMessages += '{{ $error }}\n';
        @endforeach
        alert(errorMessages);
    @endif
</script>
@endpush