@extends('users.layout')

@section('content')
    <div class="success-message">
        <h2>Pemesanan Berhasil!</h2>
        <p>Terima kasih telah melakukan pemesanan. Anda dapat melakukan pembayaran melalui metode yang tersedia.</p>
        <a href="{{ route('user.booking.reguler') }}" class="btn btn-primary">Kembali ke Halaman Pemesanan</a>
    </div>
@endsection
