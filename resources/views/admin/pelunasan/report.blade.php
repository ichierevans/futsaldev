@extends('layouts.admin')

@section('title', 'Laporan Pelunasan')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Laporan Pelunasan</h3>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.pelunasan.report') }}" class="mb-3">
                <div class="input-group">
                    <input type="text" 
                           name="search" 
                           class="form-control" 
                           placeholder="Cari nama customer" 
                           value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i> Cari
                        </button>
                    </div>
                </div>
            </form>

            @if($pelunasanList->count() > 0)
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Customer</th>
                            <th>Lapangan</th>
                            <th>Tanggal Booking</th>
                            <th>Total Harga</th>
                            <th>DP Terbayar</th>
                            <th>Total Dibayar</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pelunasanList as $index => $booking)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $booking->user->name }}</td>
                                <td>{{ $booking->lapangan->nama }}</td>
                                <td>{{ $booking->tanggal->format('d M Y') }}</td>
                                <td>Rp. {{ number_format($booking->total_harga, 0, ',', '.') }}</td>
                                <td>Rp. {{ number_format($booking->jumlah_dp ?? 0, 0, ',', '.') }}</td>
                                <td>Rp. {{ number_format($booking->total_harga - ($booking->remaining_amount ?? 0), 0, ',', '.') }}</td>
                                <td>
                                    @switch($booking->pelunasan->payment_status ?? null)
                                        @case('completed')
                                            <span class="badge bg-success">Lunas</span>
                                            @break
                                        @case('partial')
                                            <span class="badge bg-warning">Bayar Sebagian</span>
                                            @break
                                        @default
                                            <span class="badge bg-danger">Belum Bayar</span>
                                    @endswitch
                                </td>
                                <td>
                                    <a href="{{ route('admin.pelunasan.detail', $booking->id) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{ $pelunasanList->appends(request()->input())->links() }}
            @else
                <div class="alert alert-info text-center">
                    Tidak ada data pelunasan
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 