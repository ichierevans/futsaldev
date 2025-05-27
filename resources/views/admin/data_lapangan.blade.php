<!-- resources/views/admin/data_lapangan.blade.php -->
@extends('layouts.admin')

@section('title', 'Data Lapangan')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Data Lapangan</h2>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addLapanganModal">
                Tambah Lapangan
            </button>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Gambar</th>
                                <th>Deskripsi</th>
                                <th>Harga Siang</th>
                                <th>Harga Malam</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lapangans as $key => $lapangan)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $lapangan->nama }}</td>
                                    <td>
                                        <img src="{{ asset('storage/' . $lapangan->image) }}" 
                                             alt="{{ $lapangan->nama }}" 
                                             style="max-width: 100px;">
                                    </td>
                                    <td>{{ $lapangan->deskripsi }}</td>
                                    <td>Rp {{ number_format($lapangan->harga_siang, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($lapangan->harga_malam, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="badge {{ $lapangan->status === 'tersedia' ? 'bg-success' : 'bg-danger' }}">
                                            {{ ucfirst(str_replace('_', ' ', $lapangan->status)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" 
                                                data-bs-target="#editLapanganModal{{ $lapangan->id }}">
                                            Edit
                                        </button>
                                        <form action="{{ route('admin.lapangan.destroy', $lapangan) }}" method="POST" 
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" 
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus lapangan ini?')">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Edit Lapangan Modal -->
                                <div class="modal fade" id="editLapanganModal{{ $lapangan->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Lapangan</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('admin.lapangan.update', $lapangan) }}" method="POST" 
                                                  enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="nama" class="form-label">Nama Lapangan</label>
                                                        <input type="text" class="form-control" id="nama" name="nama" 
                                                               value="{{ $lapangan->nama }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="image" class="form-label">Gambar Lapangan</label>
                                                        <input type="file" class="form-control" id="image" name="image">
                                                        <small class="text-muted">Biarkan kosong jika tidak ingin mengubah gambar</small>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="deskripsi" class="form-label">Deskripsi</label>
                                                        <textarea class="form-control" id="deskripsi" name="deskripsi" 
                                                                  rows="3" required>{{ $lapangan->deskripsi }}</textarea>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="harga_siang" class="form-label">Harga Siang</label>
                                                        <input type="number" class="form-control" id="harga_siang" 
                                                               name="harga_siang" value="{{ $lapangan->harga_siang }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="harga_malam" class="form-label">Harga Malam</label>
                                                        <input type="number" class="form-control" id="harga_malam" 
                                                               name="harga_malam" value="{{ $lapangan->harga_malam }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="status" class="form-label">Status</label>
                                                        <select class="form-select" id="status" name="status" required>
                                                            <option value="tersedia" {{ $lapangan->status === 'tersedia' ? 'selected' : '' }}>
                                                                Tersedia
                                                            </option>
                                                            <option value="tidak_tersedia" {{ $lapangan->status === 'tidak_tersedia' ? 'selected' : '' }}>
                                                                Tidak Tersedia
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
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Tidak ada data lapangan</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Lapangan Modal -->
    <div class="modal fade" id="addLapanganModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Lapangan Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.lapangan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Lapangan</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Gambar Lapangan</label>
                            <input type="file" class="form-control" id="image" name="image" required>
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="harga_siang" class="form-label">Harga Siang</label>
                            <input type="number" class="form-control" id="harga_siang" name="harga_siang" required>
                        </div>
                        <div class="mb-3">
                            <label for="harga_malam" class="form-label">Harga Malam</label>
                            <input type="number" class="form-control" id="harga_malam" name="harga_malam" required>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="tersedia">Tersedia</option>
                                <option value="tidak_tersedia">Tidak Tersedia</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
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
