<!-- resources/views/admin/dashboard.blade.php -->
@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Data Customer Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Data Customer</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $customerCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <a href="{{ route('admin.data.customer') }}" class="btn btn-primary btn-sm">more info »</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Lapangan Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Data Lapangan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $fieldCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-futbol fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <a href="{{ route('admin.data.lapangan') }}" class="btn btn-success btn-sm">more info »</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Transaksi/Booking Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Data Validasi</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $bookingCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <a href="{{ route('admin.transaksi') }}" class="btn btn-warning btn-sm">more info »</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Any additional dashboard scripts can be added here
});
</script>
@endsection