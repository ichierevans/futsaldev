<!-- resources/views/admin/ubah_password.blade.php -->
@extends('layouts.admin')

@section('title', 'Ubah Password')

@section('content')
    <h2>Ubah Password</h2>
    <div class="card">
        <div class="card-body">
            <form>
                <div class="mb-3">
                    <label for="current_password" class="form-label">Password Lama</label>
                    <input type="password" class="form-control" id="current_password" required>
                </div>
                <div class="mb-3">
                    <label for="new_password" class="form-label">Password Baru</label>
                    <input type="password" class="form-control" id="new_password" required>
                </div>
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Konfirmasi Password Baru</label>
                    <input type="password" class="form-control" id="confirm_password" required>
                </div>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </form>
        </div>
    </div>
@endsection