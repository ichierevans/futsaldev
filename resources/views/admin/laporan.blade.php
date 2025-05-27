<!-- resources/views/admin/laporan.blade.php -->
@extends('layouts.admin')

@section('title', 'Laporan')

@section('content')
<div class="container-fluid">
    <h1 class="mt-4">Laporan Transaksi</h1>
    
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.laporan') }}" method="GET">
                <div class="row">
                    <div class="col-md-5">
                        <label for="dari" class="form-label">Dari</label>
                        <input type="date" class="form-control" id="dari" name="dari" value="{{ $startDate ?? '' }}">
                    </div>
                    <div class="col-md-5">
                        <label for="sampai" class="form-label">Sampai</label>
                        <input type="date" class="form-control" id="sampai" name="sampai" value="{{ $endDate ?? '' }}">
                    </div>
                    
                </div>
            </form>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Daftar Transaksi Terkonfirmasi
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tanggal</th>
                            <th>Customer</th>
                            <th>Lapangan</th>
                            <th>Durasi</th>
                            <th>Total</th>
                            <th>Bukti DP</th>
                            <th>Bukti Pelunasan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $totalRevenue = 0 @endphp
                        @foreach($bookings as $booking)
                        <tr>
                            <td>{{ $booking->id }}</td>
                            <td>{{ \Carbon\Carbon::parse($booking->tanggal)->format('d-m-Y') }}</td>
                            <td>{{ $booking->customer?->name ?? 'N/A' }}</td>
                            <td>{{ $booking->field?->nama ?? 'N/A' }}</td>
                            <td>
                                @php
                                    $startTime = \Carbon\Carbon::parse($booking->jam_mulai);
                                    $endTime = \Carbon\Carbon::parse($booking->jam_selesai);
                                    $duration = $startTime->diffInHours($endTime);
                                @endphp
                                {{ $duration }} jam
                            </td>
                            <td>Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</td>
                            <td>
                                @if($booking->payment_proof)
                                    <a href="#" class="btn btn-sm btn-info" data-bs-toggle="modal" 
                                       data-bs-target="#dpPaymentProofModal{{ $booking->id }}">
                                        Lihat Bukti DP
                                    </a>
                                @else
                                    Belum Ada
                                @endif
                            </td>
                            <td>
                                @if($booking->pelunasan && $booking->pelunasan->payment_proof)
                                    <a href="#" class="btn btn-sm btn-success" data-bs-toggle="modal" 
                                       data-bs-target="#pelunasanPaymentProofModal{{ $booking->id }}">
                                        Lihat Bukti Pelunasan
                                    </a>
                                @else
                                    Belum Ada
                                @endif
                            </td>
                            @php $totalRevenue += $booking->total_harga @endphp
                        </tr>

                        <!-- DP Payment Proof Modal -->
                        @if($booking->payment_proof)
                        <div class="modal fade" id="dpPaymentProofModal{{ $booking->id }}" tabindex="-1" aria-labelledby="dpPaymentProofModalLabel{{ $booking->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="dpPaymentProofModalLabel{{ $booking->id }}">Bukti Pembayaran DP Booking #{{ $booking->id }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-center">
                                        <img src="{{ asset('storage/' . $booking->payment_proof) }}" 
                                             alt="Bukti Pembayaran DP" 
                                             class="img-fluid" 
                                             style="max-width: 100%; max-height: 500px; object-fit: contain;">
                                        <div class="mt-3">
                                            <strong>Bank:</strong> {{ $booking->payment_bank ?? 'Tidak Tersedia' }}<br>
                                            <strong>Tanggal Pembayaran DP:</strong> {{ $booking->payment_date ? \Carbon\Carbon::parse($booking->payment_date)->format('d-m-Y H:i:s') : 'Tidak Tersedia' }}
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

                        <!-- Pelunasan Payment Proof Modal -->
                        @if($booking->pelunasan && $booking->pelunasan->payment_proof)
                        <div class="modal fade" id="pelunasanPaymentProofModal{{ $booking->id }}" tabindex="-1" aria-labelledby="pelunasanPaymentProofModalLabel{{ $booking->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="pelunasanPaymentProofModalLabel{{ $booking->id }}">Bukti Pembayaran Pelunasan Booking #{{ $booking->id }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-center">
                                        <img src="{{ asset('storage/dp_payments/' . $booking->pelunasan->payment_proof) }}" 
                                             alt="Bukti Pembayaran Pelunasan" 
                                             class="img-fluid" 
                                             style="max-width: 100%; max-height: 500px; object-fit: contain;">
                                        <div class="mt-3">
                                            <strong>Bank:</strong> {{ $booking->pelunasan->payment_method ?? 'Tidak Tersedia' }}<br>
                                            <strong>Tanggal Pembayaran Pelunasan:</strong> {{ $booking->pelunasan->paid_at ? \Carbon\Carbon::parse($booking->pelunasan->paid_at)->format('d-m-Y H:i:s') : 'Tidak Tersedia' }}
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <a href="{{ asset('storage/dp_payments/' . $booking->pelunasan->payment_proof) }}" 
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
                    <tfoot>
                        <tr>
                            <th colspan="5" class="text-right">Total Pendapatan</th>
                            <th>Rp {{ number_format($totalRevenue, 0, ',', '.') }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            "order": [[0, "desc"]]
        });
    });
</script>
@endpush
