@extends('layouts.admin')

@section('title', 'Detail Pelunasan')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
    .success-modal-container {
        background: linear-gradient(135deg, #f6f8f9 0%, #e5ebee 100%);
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        padding: 20px;
        text-align: center;
    }
    .success-modal-icon {
        font-size: 80px;
        color: #28a745;
        margin-bottom: 20px;
        animation: bounce 1s ease;
    }
    .success-modal-title {
        font-weight: bold;
        color: #28a745;
        margin-bottom: 15px;
    }
    .success-modal-details {
        background-color: rgba(255,255,255,0.7);
        border-radius: 10px;
        padding: 15px;
        margin-top: 20px;
        text-align: left;
    }
    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% {transform: translateY(0);}
        40% {transform: translateY(-20px);}
        60% {transform: translateY(-10px);}
    }
    .payment-confirmation-modal {
        max-width: 500px !important;
        width: 100%;
        border-radius: 15px !important;
    }
    .payment-confirmation-modal .swal2-title {
        color: #4a4a4a;
        font-weight: 600;
        margin-bottom: 15px;
    }
    .payment-details-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 10px;
    }
    .payment-details-table tr {
        background-color: #f8f9fa;
        border-radius: 8px;
    }
    .payment-details-table th, 
    .payment-details-table td {
        padding: 10px 15px;
        text-align: left;
    }
    .payment-details-table th {
        color: #6c757d;
        font-weight: 500;
        width: 50%;
    }
    .payment-details-table td {
        color: #343a40;
        font-weight: 600;
    }
    .swal2-confirm {
        background-color: #28a745 !important;
        margin-right: 10px;
    }
    .swal2-cancel {
        background-color: #dc3545 !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Detail Pembayaran Booking</h3>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Informasi Booking</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th>Nama Customer</th>
                                    <td>{{ $booking->user->name }}</td>
                                </tr>
                                <tr>
                                    <th>Lapangan</th>
                                    <td>{{ $booking->lapangan->nama }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Booking</th>
                                    <td>{{ $booking->tanggal->format('d M Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Jam</th>
                                    <td>{{ $booking->jam_mulai }} - {{ $booking->jam_selesai }}</td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="col-md-6">
                            <h5>Detail Pembayaran</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th>Total Harga</th>
                                    <td>Rp. {{ number_format($booking->total_harga, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th>DP Terbayar</th>
                                    <td>Rp. {{ number_format($booking->remaining_amount ?? 0, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th>Sisa Pembayaran</th>
                                    <td>Rp. {{ number_format($booking->remaining_amount ?? 0, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th>Status Pelunasan</th>
                                    <td>
                                        @switch($booking->payment_status)
                                            @case('pending')
                                                <span class="badge bg-danger">Belum Bayar</span>
                                                @break
                                            @case('partial')
                                                <span class="badge bg-warning">Bayar Sebagian</span>
                                                @break
                                            @case('completed')
                                                <span class="badge bg-success">Lunas</span>
                                                @break
                                        @endswitch
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($booking->payment_status !== 'completed')
                    <div class="card mt-3">
                        <div class="card-header">
                            <h3 class="card-title">Proses Pelunasan</h3>
                        </div>
                        <div class="card-body">
                            @if(!$booking->remaining_amount)
                            <div class="alert alert-danger">
                                <strong>Belum Bayar DP</strong>
                                <p>Silakan lakukan pembayaran DP terlebih dahulu</p>
                            </div>
                            @else
                            <div class="alert alert-info">
                                <strong>Informasi DP</strong>
                                <p>Jumlah DP: Rp. {{ number_format($booking->remaining_amount, 0, ',', '.') }}</p>
                                <p>Metode Pembayaran: {{ $booking->dp_payment_method }}</p>
                                <p>Tanggal Pembayaran DP: {{ $booking->dp_payment_date ? $booking->dp_payment_date->format('d M Y H:i') : 'N/A' }}</p>
                            </div>
                            
                            <form id="pelunasanForm" 
                                  action="{{ route('admin.pelunasan.dp', $booking->id) }}" 
                                  method="POST" 
                                  enctype="multipart/form-data"
                                  novalidate
                                  class="needs-validation">
                                @csrf
                                <div class="form-group">
                                    <label for="payment_amount">Jumlah Pelunasan</label>
                                    <input type="number" 
                                           class="form-control" 
                                           id="payment_amount" 
                                           name="payment_amount" 
                                           value="{{ $booking->remaining_amount }}" 
                                           max="{{ $booking->remaining_amount }}" 
                                           placeholder="Masukkan jumlah pembayaran"
                                           required>
                                    <small class="form-text text-muted">
                                        Sisa Pembayaran: Rp. {{ number_format($booking->remaining_amount, 0, ',', '.') }}
                                    </small>
                                </div>

                                <div class="form-group">
                                    <label for="payment_method">Metode Pembayaran</label>
                                    <select class="form-control" id="payment_method" name="payment_method" required>
                                        <option value="">Pilih Metode Pembayaran</option>
                                        <option value="BCA">BCA</option>
                                        <option value="Mandiri">Mandiri</option>
                                        <option value="BRI">BRI</option>
                                        <option value="BNI">BNI</option>
                                        <option value="Cash">Cash</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="payment_proof">Bukti Pembayaran Pelunasan</label>
                                    <input type="file" 
                                           class="form-control-file" 
                                           id="payment_proof" 
                                           name="payment_proof" 
                                           accept=".jpg,.jpeg,.png,.pdf">
                                    <small class="form-text text-muted">
                                        Format: JPG, JPEG, PNG, PDF (maks. 2MB)
                                    </small>
                                </div>

                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" 
                                               class="custom-control-input" 
                                               id="is_manual_input" 
                                               name="is_manual_input" 
                                               value="1">
                                        <label class="custom-control-label" for="is_manual_input">
                                            Pembayaran Manual (Tanpa Bukti)
                                        </label>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary btn-block" id="prosesPelunasanBtn">
                                    <i class="fas fa-check-circle"></i> Proses Pelunasan
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(function() {
        // Ensure jQuery and SweetAlert are available
        if (!window.jQuery) {
            alert('jQuery is missing. Please contact support.');
            return;
        }

        if (!window.Swal) {
            alert('SweetAlert is missing. Please contact support.');
            return;
        }

        // Payment processing confirmation
        $('#prosesPelunasanBtn').on('click', function(e) {
            e.preventDefault();

            // Collect payment details
            const $form = $(this).closest('form');
            const paymentAmount = $('#payment_amount').val();
            const paymentMethod = $('#payment_method').val() || 'Tidak Ditentukan';
            const remainingAmount = {{ $booking->remaining_amount }};

            // Show confirmation popup
            Swal.fire({
                title: 'Konfirmasi Pembayaran',
                html: `
                    <div class="text-left" style="max-width: 400px; margin: 0 auto;">
                        <h4 class="text-center mb-3">Detail Pembayaran</h4>
                        <table class="table">
                            <tr>
                                <th>Jumlah Pembayaran</th>
                                <td>Rp. ${new Intl.NumberFormat('id-ID').format(paymentAmount)}</td>
                            </tr>
                            <tr>
                                <th>Metode Pembayaran</th>
                                <td>${paymentMethod}</td>
                            </tr>
                            <tr>
                                <th>Sisa Pembayaran</th>
                                <td>Rp. ${new Intl.NumberFormat('id-ID').format(remainingAmount - paymentAmount)}</td>
                            </tr>
                        </table>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#dc3545',
                confirmButtonText: 'Ya, Proses Pembayaran',
                cancelButtonText: 'Batal',
                preConfirm: () => {
                    // Submit the form
                    $form.submit();
                }
            });
        });
    });
</script>
@endpush 