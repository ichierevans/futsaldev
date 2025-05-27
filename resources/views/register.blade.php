<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Register - FutZone</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="{{ asset('../assets/css/register.css') }}" rel="stylesheet">
    

</head>

<body>

    <div class="navbar">
        <a href="/" class="btn-back">Kembali</a>
        <div class="brand">Register</div>
        <div class="brand">FutZone</div>
    </div>

    <div class="form-container">
        <form action="/register" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Nama Lengkap</label>
                <input type="text" name="name" placeholder="Masukkan Nama Lengkap" required>
            </div>

            <div class="row">
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" name="email" placeholder="email..." required>
                </div>
                <div class="form-group">
                    <label for="phone">No.Hp/Whatsapp</label>
                    <input type="tel" name="phone" placeholder="+62.." required pattern="[0-9]{10,13}" maxlength="13"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                </div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Masukkan Password" required>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Konfirmasi Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                    placeholder="Masukkan Password Lagi" required>
                <div id="confirmationMessage" style="color: red;"></div> <!-- Notifikasi untuk konfirmasi password -->
            </div>

            <button class="btn" type="submit" id="registerBtn">Register</button>

            <script>
                const registerBtn = document.getElementById('registerBtn');
                const passwordField = document.getElementById('password');
                const passwordConfirmationField = document.getElementById('password_confirmation');
                const confirmationMessage = document.getElementById('confirmationMessage');

                // Menambahkan event listener untuk memastikan password dan konfirmasi password sama
                registerBtn.addEventListener('click', function (event) {
                    const password = passwordField.value;
                    const confirmPassword = passwordConfirmationField.value;

                    // Validasi konfirmasi password
                    if (password !== confirmPassword) {
                        confirmationMessage.textContent = 'Password dan konfirmasi password tidak cocok!';
                        event.preventDefault(); // Menghentikan form submission jika password tidak cocok
                    } else {
                        confirmationMessage.textContent = ''; // Menghapus pesan jika password cocok
                    }
                });

                // Validasi password lainnya (misal, panjang minimal, kombinasi huruf dan angka, dll.)
                passwordField.addEventListener('input', function () {
                    const password = passwordField.value;
                    const regex = /^(?=.[A-Z])(?=.\d)(?=.[#@!$%^&])[A-Za-z\d#@!$%^&*]{8,}$/;

                    if (regex.test(password)) {
                        // Tambahkan pesan validasi password jika perlu
                    } else {
                        // Tambahkan pesan kesalahan jika password tidak valid (misal, jika tidak memenuhi kriteria)
                    }
                });
            </script>


            <div class="footer-text">
                Sudah punya akun? <a href="/login">Login</a>.
            </div>
        </form>
    </div>

</body>

</html>