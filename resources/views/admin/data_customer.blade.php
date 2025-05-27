<!-- resources/views/admin/data_customer.blade.php -->
@extends('layouts.admin')

@section('title', 'Data Customer')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Data Customer</h2>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
                Tambah Customer
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
                                <th>No.Hp</th>
                                <th>E-mail</th>
                                <th>password</th>
                                <th>aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customers as $key => $customer)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $customer->name }}</td>
                                    <td>{{ $customer->phone }}</td>
                                    <td>{{ $customer->email }}</td>
                                    <td>*****</td>
                                    <td>
                                        <button class="btn btn-success btn-sm" data-bs-toggle="modal" 
                                                data-bs-target="#editCustomerModal{{ $customer->id }}">
                                            edite
                                        </button>
                                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#viewCustomerModal{{ $customer->id }}">
                                            Lihat
                                        </button>
                                        <form action="{{ route('admin.customer.destroy', $customer) }}" method="POST" 
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" 
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus customer ini?')">
                                                delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- View Customer Modal -->
                                <div class="modal fade" id="viewCustomerModal{{ $customer->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Detail Customer</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Nama:</label>
                                                    <p>{{ $customer->name }}</p>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Email:</label>
                                                    <p>{{ $customer->email }}</p>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">No. Telepon:</label>
                                                    <p>{{ $customer->phone }}</p>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Alamat:</label>
                                                    <p>{{ $customer->address ?: '-' }}</p>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Edit Customer Modal -->
                                <div class="modal fade" id="editCustomerModal{{ $customer->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Customer</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('admin.customer.update', $customer) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="name" class="form-label">Nama</label>
                                                        <input type="text" class="form-control" id="name" name="name" 
                                                               value="{{ $customer->name }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="email" class="form-label">Email</label>
                                                        <input type="email" class="form-control" id="email" name="email" 
                                                               value="{{ $customer->email }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="phone" class="form-label">No. Telepon</label>
                                                        <input type="text" class="form-control" id="phone" name="phone" 
                                                               value="{{ $customer->phone }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="password" class="form-label">Password Baru (Kosongkan jika tidak ingin mengubah)</label>
                                                        <input type="password" class="form-control" id="password" name="password">
                                                        <small class="text-muted">Minimal 6 karakter</small>
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
                                    <td colspan="6" class="text-center">Tidak ada data customer</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-end mt-3">
                    {{ $customers->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Add Customer Modal -->
    <div class="modal fade" id="addCustomerModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Customer Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.customer.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">No. Telepon</label>
                            <input type="text" class="form-control" id="phone" name="phone" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                            <small class="text-muted">Minimal 6 karakter</small>
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