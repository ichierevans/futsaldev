@extends('layouts.admin')

@section('title', 'Daftar Pelunasan Terkonfirmasi')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Pelunasan Terkonfirmasi</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Customer</th>
                        <th>Lapangan</th>
                        <th>Tanggal Booking</th>
                        <th>Total Harga</th>
                        <th>Jumlah DP</th>
                        <th>Sisa Pembayaran</th>
                        <th>Status Pelunasan</th>
                        <th>Aksi</th>
                        <th>sudah dibayar</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($pelunasanList as $index => $booking)
        @php
            $isLunas = optional($booking->pelunasan)->payment_status === 'completed';
            $jumlahDp = $isLunas ? 0 : ($booking->jumlah_dp ?? 0);
            $sisaPembayaran = $isLunas ? 0 : ($booking->remaining_amount ?? 0);
            $sudahDibayar = $isLunas ? $booking->total_harga : ($booking->jumlah_dp ?? 0);
        @endphp
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $booking->user->name }}</td>
            <td>{{ $booking->lapangan->nama }}</td>
            <td>{{ $booking->tanggal->format('d M Y') }}</td>
            <td>Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</td>
            <td>Rp {{ number_format($jumlahDp, 0, ',', '.') }}</td>
            <td>Rp {{ number_format($sisaPembayaran, 0, ',', '.') }}</td>
            <td>
                @switch(optional($booking->pelunasan)->payment_status)
                    @case('completed')
                        <span class="badge bg-success">Lunas</span>
                        @break
                    @case('partial')
                        <span class="badge bg-warning">Bayar Sebagian</span>
                        @break
                    @default
                        <span class="badge bg-danger">Belum Lunas</span>
                @endswitch
            </td>
            <td>
                <a href="{{ route('admin.pelunasan.detail', $booking->id) }}" class="btn btn-sm btn-info">
                    <i class="fas fa-eye"></i> Detail
                </a>
            </td>
            <td>Rp {{ number_format($sudahDibayar, 0, ',', '.') }}</td>
        </tr>
    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Check for success message from session
        @if(session('success'))
            Swal.fire({
                title: 'Pembayaran Berhasil!',
                html: `
                    <div class="text-left" style="max-width: 400px; margin: 0 auto;">
                        <p>{{ session('success') }}</p>
                    </div>
                `,
                icon: 'success',
                confirmButtonColor: '#28a745',
                confirmButtonText: 'OK'
            });
        @endif
    });
</script>
@endpush 