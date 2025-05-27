@extends('users.layout')

@section('styles')
<style>
    .password-container {
        background-color: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        max-width: 600px;
        margin: 20px auto;
    }

    .password-header {
        background-color: #28a745;
        color: white;
        padding: 15px 20px;
        border-radius: 10px;
        margin-bottom: 25px;
    }

    .password-header h4 {
        margin: 0;
        font-weight: bold;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        font-weight: 500;
        color: #333;
        margin-bottom: 8px;
        display: block;
    }

    .form-control {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #28a745;
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        outline: none;
    }

    .password-requirements {
        background-color: #f8f9fa;
        padding: 15px 20px;
        border-radius: 8px;
        margin: 20px 0;
    }

    .requirement-list {
        list-style: none;
        padding: 0;
        margin: 10px 0 0;
    }

    .requirement-list li {
        color: #666;
        font-size: 13px;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
    }

    .requirement-list li::before {
        content: "•";
        color: #28a745;
        font-weight: bold;
        margin-right: 8px;
    }

    .btn-submit {
        background-color: #28a745;
        color: white;
        border: none;
        padding: 12px 25px;
        border-radius: 8px;
        font-weight: 500;
        font-size: 15px;
        cursor: pointer;
        width: 100%;
        transition: all 0.3s ease;
    }

    .btn-submit:hover {
        background-color: #218838;
        transform: translateY(-1px);
    }

    .btn-submit:active {
        transform: translateY(0);
    }

    .alert {
        padding: 12px 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 14px;
    }

    .alert-success {
        background-color: #d4edda;
        border: 1px solid #c3e6cb;
        color: #155724;
    }

    .alert-danger {
        background-color: #f8d7da;
        border: 1px solid #f5c6cb;
        color: #721c24;
    }

    .password-toggle {
        position: relative;
    }

    .toggle-password {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        border: none;
        background: none;
        color: #666;
        cursor: pointer;
        padding: 0;
    }

    .toggle-password:focus {
        outline: none;
        color: #28a745;
    }

    .invalid-feedback {
        color: #dc3545;
        font-size: 13px;
        margin-top: 5px;
        display: block;
    }

    @media (max-width: 768px) {
        .password-container {
            margin: 15px;
            padding: 20px;
        }
    }
</style>
@endsection

@section('content')
<div class="password-container">
    <div class="password-header">
        <h4>Ubah Password</h4>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('user.password.update') }}" method="POST">
        @csrf
        
        <div class="form-group">
            <label class="form-label" for="current_password">Password Saat Ini</label>
            <div class="password-toggle">
                <input type="password" 
                       class="form-control @error('current_password') is-invalid @enderror" 
                       id="current_password" 
                       name="current_password" 
                       required>
                <button type="button" class="toggle-password" onclick="togglePassword('current_password')">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
            @error('current_password')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="password">Password Baru</label>
            <div class="password-toggle">
                <input type="password" 
                       class="form-control @error('password') is-invalid @enderror" 
                       id="password" 
                       name="password" 
                       required>
                <button type="button" class="toggle-password" onclick="togglePassword('password')">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
            @error('password')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="password_confirmation">Konfirmasi Password Baru</label>
            <div class="password-toggle">
                <input type="password" 
                       class="form-control" 
                       id="password_confirmation" 
                       name="password_confirmation" 
                       required>
                <button type="button" class="toggle-password" onclick="togglePassword('password_confirmation')">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
        </div>

        <div class="password-requirements">
            <strong>Password harus memenuhi kriteria berikut:</strong>
            <ul class="requirement-list">
                <li>Minimal 8 karakter</li>
                <li>Mengandung huruf besar dan huruf kecil</li>
                <li>Mengandung angka</li>
                <li>Mengandung karakter khusus (!@#$%^&*)</li>
            </ul>
        </div>

        <button type="submit" class="btn-submit">
            Ubah Password
        </button>
    </form>
</div>
@endsection

@section('scripts')
<script>
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const button = input.nextElementSibling;
    const icon = button.querySelector('i');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Validasi password saat diketik
document.getElementById('password').addEventListener('input', function(e) {
    const password = e.target.value;
    const requirements = {
        length: password.length >= 8,
        case: /[a-z]/.test(password) && /[A-Z]/.test(password),
        number: /\d/.test(password),
        special: /[!@#$%^&*]/.test(password)
    };
    
    // Update visual feedback di requirement list
    const requirementItems = document.querySelectorAll('.requirement-list li');
    requirementItems.forEach((item, index) => {
        const requirement = Object.values(requirements)[index];
        item.style.color = requirement ? '#28a745' : '#666';
    });
});
</script>
@endsection