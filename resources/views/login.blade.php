<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - FutZone</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link href="{{ asset('assets/css/login.css') }}" rel="stylesheet">
   

    

    <style>
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .password-container {
            position: relative;
        }
        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #888;
            z-index: 10;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <a href="/" class="btn-back">Kembali</a>
        <div><strong>LOGIN</strong></div>
        <div class="brand">FutZone</div>
    </div>

    <!-- Flash Message: Sukses Registrasi -->
    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Flash Message: Error Login -->
    @if($errors->any())
        <div class="alert-danger">
            {{ $errors->first() }}
        </div>
    @endif

    <!-- Login Form -->
    <div class="login-form">
        <form method="POST" action="{{ route('login.process') }}">
            @csrf
            <div class="form-group">
                <input type="email" name="email" placeholder="Masukkan E-mail" required>
            </div>
            <div class="form-group password-container">
                <input type="password" name="password" id="password" placeholder="Password" required>
                <span class="toggle-password" onclick="togglePasswordVisibility()">
                    <i id="passwordToggleIcon" class="fas fa-eye-slash"></i>
                </span>
            </div>
            <button type="submit" class="btn">LOGIN</button>
        </form>
        <div class="register-link">
            Belum punya akun? <a href="{{ route('register') }}">Register</a>
        </div>
    </div>

    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const passwordToggleIcon = document.getElementById('passwordToggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordToggleIcon.classList.remove('fa-eye-slash');
                passwordToggleIcon.classList.add('fa-eye');
            } else {
                passwordInput.type = 'password';
                passwordToggleIcon.classList.remove('fa-eye');
                passwordToggleIcon.classList.add('fa-eye-slash');
            }
        }
    </script>
</body>
</html>