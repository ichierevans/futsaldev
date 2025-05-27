<!DOCTYPE html> 
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <link rel="shortcut icon" href="{{ asset('assets/icon/iconfix.png') }}">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <title>Futzone</title>
        <link rel="stylesheet" href="{{asset('assets/css/styles.css')}}">
        <script src="{{ asset('assets/js/scorll.js') }}"></script>
        <script type="importmap">
        {
            "imports": {
                "@google/generative-ai": "https://esm.run/@google/generative-ai",
                "markdown-it": "https://esm.run/markdown-it"
            }
        }
        </script>
    </head>
 

    <body>
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light py-2 fixed-top">
            <div class="container">
                <a class="navbar-brand" href="#">  
                    <h2 class="fw-bold text-success">FutZone</h2>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">HOME</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#jadwal-lapangan">JADWAL LAPANGAN</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#lapangan-tersedia">LAPANGAN</a>
                        </li>
                        <li clas    s="nav-item">
                            <a class="nav-link" href="#" id="bookingLink">BOOKING</a>
                        </li>
                    </ul>
                    <div class="d-flex gap-3">
                        <a href="{{ route('login') }}" class="btn btn-outline-success">LOGIN</a>
                        <a href="{{ route('register') }}" class="btn btn-success">REGISTER</a>
                    </div>
                </div>
            </div>
        </nav>
        <!-- End Navbar -->
        
        <!-- Hero Section -->
<section id="hero" class="d-flex align-items-center justify-content-center">
    <div class="container text-center text-white">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <h3>Welcome To</h3>
                <h1 class="display-3 fw-bold mb-4">FutZone</h1>
                <p class="lead mb-4">Mudah, Praktis dan anda dapat melihat ketersediaan jadwal, memilih lapangan, serta melakukan pembayaran secara digital tanpa perlu datang langsung ke lokasi.</p>
                <a href="#" class="btn btn-success btn-lg" id="heroBookingBtn">Booking</a>
            </div>
        </div>
    </div>
</section>

        <!-- End Hero Section -->
        
       <!-- About Section -->
<section id="about" class="py-5">
  <div class="container">
      <div class="row align-items-center">
          <div class="col-lg-6">
              <div class="image-container">
                  <img src="{{asset('assets/image/bg2.png')}}" alt="FutZone Building" class="img-fluid rounded">
              </div>
          </div>
          <div class="col-lg-6 ps-lg-5 mt-4 mt-lg-0">
              <div class="about-content">
                  <h6 class="text-uppercase fw-bold text-success mb-1">Sejarah</h6>
                  <h2 class="display-5 fw-bold mb-4">FutZone</h2>
                  <p class="mb-3">Didirikan untuk memenuhi kebutuhan akan lapangan futsal berkualitas, kami telah berkembang menjadi tempat favorit para pecinta futsal.</p>
                  <p>Dengan fasilitas modern dan layanan terbaik, kami terus berkomitmen memberikan pengalaman bermain yang nyaman dan menyenangkan.</p>
              </div>
          </div>
      </div>
  </div>
</section>
        <!-- Fields Section -->
        <section id="lapangan-tersedia" class="py-5">
  <div class="container">
      <h2 class="text-center mb-5">Lapangan Tersedia</h2>
      
      <div class="lapangan-scroll-container">
          <div class="lapangan-wrapper">
              @forelse($lapangans as $lapangan)
              <div class="lapangan-card">
                  <div class="card h-100 shadow-sm">
                      <img src="{{ asset('storage/' . $lapangan->image) }}" class="card-img-top" alt="{{ $lapangan->nama }}">
                      <div class="card-body text-center">
                          <h5 class="card-title fw-bold text-success">{{ $lapangan->nama }}</h5>
                          <p class="card-text text-muted mb-3">{{ $lapangan->deskripsi }}</p>
                          <div class="pricing mb-3">
                              <div class="d-flex justify-content-between mb-2">
                                  <span class="text-muted">Siang (07.00-16.00)</span>
                                  <span class="fw-bold text-success">Rp{{ number_format($lapangan->harga_siang, 0, ',', '.') }}/jam</span>
                              </div>
                              <div class="d-flex justify-content-between">
                                  <span class="text-muted">Malam (17.00-22.00)</span>
                                  <span class="fw-bold text-success">Rp{{ number_format($lapangan->harga_malam, 0, ',', '.') }}/jam</span>
                              </div>
                          </div>
                          @if($lapangan->status === 'tersedia')
                          <span class="badge bg-success">Tersedia</span>
                          @else
                          <span class="badge bg-danger">Tidak Tersedia</span>
                          @endif
                      </div>
                  </div>
              </div>
              @empty
              <div class="text-center w-100">
                  <p>Tidak ada lapangan tersedia saat ini.</p>
              </div>
              @endforelse
          </div>
      </div>
  </div>
</section>

<style>
#lapangan-tersedia {
    background-color: #f8f9fa;
}

.lapangan-scroll-container {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    padding: 0 15px;
}

.lapangan-wrapper {
    display: flex;
    flex-wrap: nowrap;
    gap: 20px;
    padding: 15px 0;
}

.lapangan-card {
    flex: 0 0 280px;
    max-width: 280px;
    transition: transform 0.3s ease;
}

.lapangan-card:hover {
    transform: scale(1.03);
}

.lapangan-wrapper::-webkit-scrollbar {
    height: 8px;
}

.lapangan-wrapper::-webkit-scrollbar-track {
    background: #e9ecef;
}

.lapangan-wrapper::-webkit-scrollbar-thumb {
    background: #28a745;
    border-radius: 4px;
}

.lapangan-card .card-img-top {
    height: 200px;
    object-fit: cover;
}

.lapangan-card .pricing {
    background-color: #f1f3f5;
    padding: 10px;
    border-radius: 5px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const lapanganWrapper = document.querySelector('.lapangan-wrapper');
    
    // Smooth horizontal scrolling with mouse wheel
    lapanganWrapper.addEventListener('wheel', function(e) {
        e.preventDefault();
        this.scrollLeft += e.deltaY;
    });
});
</script>
        <!-- Booking Info Section -->
        <section id="booking-info" class="py-5 bg-light">
            <div class="container">
                <h2 class="text-center mb-4">KETENTUAN DAN INFORMASI PEMESANAN FOTZONE</h2>
                <div class="row">
                    <div class="col-lg-6">
                        <ul class="info-list">
                            <li>Untuk Setiap pemesanan diharapkan register akun kemudian login.</li>
                            <li>Harga sewa lapangan pada hari/malam [pagi-siang] & [sore-malam]: Rp.85.000/jam [malam] & Rp.95.000/jam [malam].</li>
                            <li>Setiap Pemesanan diwajibkan melakukan DP 50%.</li>
                            <li>Konformasi pembatalan maksimal 24jam sebelum bermain, jika lebih maka DP akan hangus.</li>
                            <li>Untuk booking event skala besar minimal dilakukan 1 minggu sebelumnya</li>
                        </ul>
                    </div>
                    <div class="col-lg-6">
                        <ul class="info-list">
                            <li>Tersedia 7 lapangan, 5 lapangan untuk standar, 2 lapangan untuk rumput vinyl dan lebih lebar.</li>
                            <li>Untuk pemesanan booking reguler, setiap 10 kali pemesanan maka akan mendapatkan gratis sesi 1jam dengan pilihan lapangan dan waktu yang bebas</li>
                            <li>Membership berlaku pada minimal 4 kali/jam dalam 1 bulan, serta konsumen mendapatkan diskon sebesar 10% dari total biaya.</li>
                            <li>Tidak menyediakan penyewaan atribut futsal.</li>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Booking Info Section -->

<!-- Schedule Section -->
<section id="jadwal-lapangan" class="py-5 bg-light">
  <div class="container">
      <h2 class="text-center mb-5">Jadwal Lapangan</h2>
      
      <!-- Calendar Header -->
      <div class="calendar-container">
          <div class="calendar-header">
              <h4>Kalender Booking</h4>
              <div class="filter-date">
                  <label for="date-picker" class="text-white">Pilih Tanggal:</label>
                  <input type="date" class="date-picker" id="date-picker" value="{{ date('Y-m-d') }}">
              </div>
          </div>
          <div class="selected-date-display text-center mb-3">
              Jadwal untuk: <span id="formatted-date" class="fw-bold">{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</span>
          </div>
      </div>
      
      <!-- Status Legend -->
      <div class="status-legend">
          <div class="status-label available-label">
              <i class="fas fa-circle me-2"></i> Tersedia
          </div>
          <div class="status-label booked-label">
              <i class="fas fa-circle me-2"></i> Terisi
          </div>
      </div>
      
      <!-- Schedule Table -->
      <div class="table-responsive">
          <table class="field-schedule">
              <thead>
                  <tr>
                      <th class="field-name header-corner"></th>
                      @for($hour = 7; $hour <= 21; $hour++)
                      <th>{{ sprintf('%02d.00', $hour) }}</th>
                      @endfor
                  </tr>
              </thead>
              <tbody>
                  @php
                      $lapangans = \App\Models\Lapangan::all();
                      $bookings = \App\Models\Booking::whereDate('tanggal', date('Y-m-d'))
                          ->where('status', 'confirmed')
                          ->get();
                  @endphp
                  
                  @foreach($lapangans as $lapangan)
                  <tr>
                      <td class="field-name">{{ $lapangan->nama }}</td>
                      @for($hour = 7; $hour <= 21; $hour++)
                          @php
                              $timeSlot = sprintf('%02d.00', $hour);
                              $booking = $bookings->first(function($booking) use ($lapangan, $timeSlot) {
                                  return $booking->lapangan_id == $lapangan->id && 
                                         $booking->jam_mulai <= $timeSlot && 
                                         $booking->jam_selesai > $timeSlot;
                              });
                          @endphp
                          
                          <td class="booking-cell {{ $booking ? 'booked' : 'available' }}" 
                              data-field="{{ $lapangan->id }}" 
                              data-time="{{ $timeSlot }}">
                              @if($booking)
                              <div class="booked">
                                  <div class="booker-name">{{ $booking->user->name }}</div>
                                  <div class="booking-time">
                                      {{ $booking->jam_mulai }} - {{ $booking->jam_selesai }}
                                  </div>
                              </div>
                              @endif
                          </td>
                      @endfor
                  </tr>
                  @endforeach
              </tbody>
          </table>
      </div>
  </div>
</section>

<style>
.calendar-container {
    background-color: white;
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    margin-bottom: 30px;
}

.calendar-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    background-color: #28a745;
    padding: 15px;
    border-radius: 10px;
    color: white;
}

.field-schedule {
    width: 100%;
    min-width: 1200px;
    border-collapse: separate;
    border-spacing: 0;
}

.field-schedule th, .field-schedule td {
    border: 1px solid #e0e0e0;
    text-align: center;
    padding: 8px 4px;
    position: relative;
    height: 70px;
    width: 80px;
    vertical-align: middle;
}

.field-schedule thead th {
    background-color: #28a745;
    color: white;
    font-weight: bold;
    height: auto;
    padding: 12px 4px;
    position: sticky;
    top: 0;
    z-index: 25;
}

.field-name {
    position: sticky;
    left: 0;
    background-color: #28a745;
    color: white;
    z-index: 30;
    min-width: 150px;
}

.booking-cell.booked {
    background-color: #dc3545;
    color: white;
}

.booking-cell.available {
    background-color: #ffffff;
}

.booker-name {
    font-size: 12px;
    font-weight: bold;
    margin-bottom: 4px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.booking-time {
    font-size: 11px;
    opacity: 0.9;
}

.table-responsive {
    width: 100%;
    overflow-x: auto;
    max-height: 500px;
    border-radius: 10px;
    border: 1px solid #e0e0e0;
}

.status-legend {
    display: flex;
    gap: 15px;
    justify-content: center;
    margin-bottom: 20px;
}

.status-label {
    display: inline-flex;
    align-items: center;
    padding: 6px 12px;
    border-radius: 20px;
}

.available-label {
    background-color: #28a745;
    color: white;
}

.booked-label {
    background-color: #dc3545;
    color: white;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const datePicker = document.getElementById('date-picker');
    const formattedDateElement = document.getElementById('formatted-date');
    const scheduleTable = document.querySelector('.field-schedule tbody');
    
    // Fungsi untuk memformat tanggal dalam bahasa Indonesia
    function formatDateIndonesian(dateString) {
        const date = new Date(dateString);
        const options = { 
            day: 'numeric', 
            month: 'long', 
            year: 'numeric' 
        };
        return date.toLocaleDateString('id-ID', options);
    }
    
    // Fungsi untuk mengupdate tabel jadwal
    function updateScheduleTable(data) {
        // Reset semua sel menjadi available
        const allCells = document.querySelectorAll('.booking-cell');
        allCells.forEach(cell => {
            cell.classList.remove('booked');
            cell.classList.add('available');
            cell.innerHTML = ''; // Hapus konten sebelumnya
        });

        // Update sel yang terbooked
        data.bookings.forEach(booking => {
            const lapanganId = booking.lapangan_id;
            const startHour = parseInt(booking.jam_mulai.split('.')[0]);
            
            for (let hour = startHour; hour < parseInt(booking.jam_selesai.split('.')[0]); hour++) {
                const cell = document.querySelector(
                    `.booking-cell[data-field="${lapanganId}"][data-time="${hour.toString().padStart(2, '0')}.00"]`
                );
                
                if (cell) {
                    cell.classList.remove('available');
                    cell.classList.add('booked');
                    cell.innerHTML = `
                        <div class="booked">
                            <div class="booker-name">${booking.user_name}</div>
                            <div class="booking-time">
                                ${booking.jam_mulai} - ${booking.jam_selesai}
                            </div>
                        </div>
                    `;
                }
            }
        });
    }
    
    // Inisialisasi dengan tanggal saat ini
    formattedDateElement.textContent = formatDateIndonesian(datePicker.value);
    
    // Update saat tanggal berubah
    datePicker.addEventListener('change', function() {
        // Kirim permintaan AJAX untuk memperbarui jadwal
        fetch(`/update-jadwal?date=${this.value}`)
            .then(response => response.json())
            .then(data => {
                // Perbarui tabel jadwal dengan data baru
                updateScheduleTable(data);
                
                // Perbarui tampilan tanggal
                formattedDateElement.textContent = formatDateIndonesian(this.value);
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Gagal memperbarui jadwal. Silakan coba lagi.');
            });
    });
});
</script>
        <!-- Gallery Section -->
        <section id="gallery" class="py-5">
            <div class="container">
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <img src="{{asset('assets/image/d1.jpeg')}}" alt="Gallery Image" class="img-fluid rounded">
                    </div>
                    <div class="col-md-4 mb-4">
                        <img src="{{asset('assets/image/d2.jpeg')}}" alt="Gallery Image" class="img-fluid rounded">
                    </div>
                    <div class="col-md-4 mb-4">
                        <img src="{{asset('assets/image/d3.jpeg')}}" alt="Gallery Image" class="img-fluid rounded">
                    </div>
                </div>
            </div>
        </section>
        <!-- End Gallery Section -->
        
        <!-- Footer -->
        <footer class="bg-success text-white py-4">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            <span>Jl. tidar, Kecamatan Sumbersari, Kabupaten Jember, Jawa Timur 69116, Indonesia</span>
                        </div>
                        <div class="mb-3">
                            <i class="fas fa-phone me-2"></i>
                            <span>+62 895 3654 24539</span>
                        </div>
                        <div class="mb-3">
                            <i class="fas fa-envelope me-2"></i>
                            <span>futzone@gmail.com</span>
                        </div>
                    </div>
                    <div class="col-lg-6 text-lg-end">
                        <h2 class="mb-3">FutZone</h2>
                        <p>Kami menyediakan lapangan permainan berkualitas untuk bermain yang nyaman dan aman. Dengan lapangan berkualitas, kami memastikan anda mendapat pengalaman bermain terbaik.</p>
                        <div class="social-icons">
                            <a href="#" class="text-white me-3"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="text-white"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- End Footer -->

        <!-- Login Required Modal -->
        <div class="modal fade" id="loginRequiredModal" tabindex="-1" aria-labelledby="loginRequiredModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="loginRequiredModalLabel">Login Diperlukan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Anda harus login terlebih dahulu untuk melakukan booking lapangan.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <a href="{{ route('login') }}" class="btn btn-success">Login</a>
                        <a href="{{ route('register') }}" class="btn btn-outline-success">Register</a>
                    </div>
                </div>
            </div>
        </div>
        
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
        
        <script>
            // Smooth scrolling for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        if (this.getAttribute('href') !== '#' &&
            this.getAttribute('id') !== 'bookingLink' &&
            this.getAttribute('id') !== 'heroBookingBtn') {

            e.preventDefault();

            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);

            if (targetElement) {
                const navbarHeight = document.querySelector('.navbar')?.offsetHeight || 0;
                const targetPosition = targetElement.getBoundingClientRect().top + window.pageYOffset - navbarHeight;

                smoothScrollTo(targetPosition);
            }
        }
    });
});

function smoothScrollTo(targetPosition, duration = 800) {
    const start = window.pageYOffset;
    const distance = targetPosition - start;
    let startTime = null;

    function animation(currentTime) {
        if (startTime === null) startTime = currentTime;
        const timeElapsed = currentTime - startTime;

        const run = easeInOutQuad(timeElapsed, start, distance, duration);
        window.scrollTo(0, run);

        if (timeElapsed < duration) requestAnimationFrame(animation);
    }

    function easeInOutQuad(t, b, c, d) {
        t /= d / 2;
        if (t < 1) return c / 2 * t * t + b;
        t--;
        return -c / 2 * (t * (t - 2) - 1) + b;
    }

    requestAnimationFrame(animation);
}

            
            // Show login modal for booking links
            document.addEventListener('DOMContentLoaded', function() {
                const bookingLink = document.getElementById('bookingLink');
                const heroBookingBtn = document.getElementById('heroBookingBtn');
                const loginRequiredModal = new bootstrap.Modal(document.getElementById('loginRequiredModal'));
                
                if (bookingLink) {
                    bookingLink.addEventListener('click', function(e) {
                        e.preventDefault();
                        loginRequiredModal.show();
                    });
                }
                
                if (heroBookingBtn) {
                    heroBookingBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        loginRequiredModal.show();
                    });
                }
            });

        
</script>

{{-- CHATBOX --}}<!-- Add this HTML code in your body section --><!-- Chat Widget -->
    <div class="futzone-chat-widget">
      <div class="chat-widget-container">
        <div class="chat-widget-header"> 
          <div class="header-left">
            <div class="online-indicator"></div>
            <div class="header-icon">⚽</div>
            <h4>FutZone AI</h4>
          </div>
          <div class="header-actions">
            <button class="minimize-btn" id="minimizeChat">_</button>
            <button class="close-btn" id="closeChat">×</button>
          </div>
        </div>
        
        <div class="chat-widget-body" id="chatMessages">
          <div class="chat-date-divider">
            <span>Hari Ini</span>
          </div>
          
          <div class="chat-message bot-message">
            <div class="message-bubble">
              <p id="welcomeMessage"></p>
              <span class="message-time">10:03 AM</span>
            </div>
          </div>
          
          <div class="typing-indicator">
            <span></span>
            <span></span>
            <span></span>
          </div>
        </div>
        
        <div class="quick-replies-container">
          <div class="quick-replies">
            <button class="quick-reply-btn" data-message="Cara booking lapangan?"><i class="fas fa-calendar-check"></i> Booking</button>
            <button class="quick-reply-btn" data-message="Promo"><i class="fas fa-tags"></i> Promo</button>
            <button class="quick-reply-btn" data-message="Jam operasional"><i class="fas fa-clock"></i> Jam</button>
            <button class="quick-reply-btn" data-message="Lokasi FutZone"><i class="fas fa-map-marker-alt"></i> Lokasi</button>
            <button class="quick-reply-btn" data-message="Harga lapangan"><i class="fas fa-money-bill"></i> Harga</button>
          </div>
        </div>
        
        <div class="chat-widget-footer">
          <div class="message-input-container">
            <input type="text" id="messageInput" placeholder="Ketik pesan Anda..." class="message-input">
          </div>
          <button class="send-message-btn" id="sendMessage">
            <i class="fas fa-paper-plane"></i>
          </button>
        </div>
      </div>
      
      <div class="chat-widget-button" id="toggleChat">
        <div class="chat-icon">
          <i class="fas fa-comments"></i>
        </div>
      </div>
    </div>
    
    
    <!-- JavaScript for Chat Widget -->
    <script type="module">
      import { GoogleGenerativeAI } from 'https://esm.run/@google/generative-ai';
      import MarkdownIt from 'https://esm.run/markdown-it';

      // Hapus konfigurasi safety settings yang tidak perlu
      const API_KEY = 'AIzaSyCAygW9zHNuc_DGO0OeB2uyX7sppB2-qig';
      const genAI = new GoogleGenerativeAI(API_KEY);

      document.addEventListener('DOMContentLoaded', function() {
        console.log('Chat widget script loaded'); // Tambahkan logging

        const toggleChatBtn = document.getElementById('toggleChat');
        const chatContainer = document.querySelector('.chat-widget-container');
        const closeBtn = document.getElementById('closeChat');
        const minimizeBtn = document.getElementById('minimizeChat');
        const messageInput = document.getElementById('messageInput');
        const sendMessageBtn = document.getElementById('sendMessage');
        const chatMessages = document.getElementById('chatMessages');
        const welcomeMessage = document.getElementById('welcomeMessage');

        // Logging untuk debugging
        if (!toggleChatBtn) console.error('Toggle chat button not found');
        if (!chatContainer) console.error('Chat container not found');
        if (!closeBtn) console.error('Close button not found');
        if (!minimizeBtn) console.error('Minimize button not found');
        if (!messageInput) console.error('Message input not found');
        if (!sendMessageBtn) console.error('Send message button not found');
        if (!chatMessages) console.error('Chat messages container not found');
        if (!welcomeMessage) console.error('Welcome message element not found');

        // Conversation history for context
        const conversationHistory = [];
        
        // Welcome message text
        const welcomeText = "Selamat datang di FutZone AI! Kami siap membantu Anda menemukan lapangan futsal terbaik. Ada yang bisa kami bantu hari ini?";
        
        // Tracking chat state
        let chatOpened = false;
        let isTyping = false;

        // Fungsi untuk mendapatkan waktu saat ini
        function getCurrentTime() {
          const now = new Date();
          const hours = now.getHours() % 12 || 12;
          const minutes = now.getMinutes().toString().padStart(2, '0');
          const ampm = now.getHours() >= 12 ? 'PM' : 'AM';
          return `${hours}:${minutes} ${ampm}`;
        }

        // Fungsi animasi teks
        function animateText(element, text) {
          return new Promise((resolve) => {
            let index = 0;
            
            function typeNextChar() {
              if (index < text.length) {
                element.textContent += text.charAt(index);
                index++;
                setTimeout(typeNextChar, Math.random() * 30 + 20);
              } else {
                resolve();
              }
            }
            
            element.textContent = ''; // Clear existing text
            typeNextChar();
          });
        }

        // Generate AI response
        async function generateGeminiResponse(messageText) {
          try {
            const model = genAI.getGenerativeModel({ model: "gemini-1.5-flash" });
            
            // Informasi Statis FutZone
            const FUTZONE_INFO = {
              sejarah: `FutZone didirikan untuk memenuhi kebutuhan akan lapangan futsal berkualitas di Jember. 
              Bermula dari passion para pecinta futsal, kami mengembangkan fasilitas modern yang nyaman dan berkualitas. 
              Dengan komitmen memberikan pengalaman bermain terbaik, FutZone telah menjadi destinasi utama 
              bagi komunitas futsal di wilayah Jember dan sekitarnya.`,
              
              profil: {
                nama: 'FutZone Futsal Center',
                lokasi: 'Jl. Tidar No.17, Kloncing, Karangrejo, Kec. Sumbersari, Kabupaten Jember, Jawa Timur 68124',
                telepon: '0895-3654-42639',
                email: 'futzone@gmail.com'
              },
              
              fasilitas: {
                totalLapangan: 7,
                standar: 5,
                rumputVinyl: 2
              }
            };

            // Deteksi konteks pertanyaan
            const konteksPertanyaan = {
              identitas: ['siapa aku', 'namamu', 'kamu siapa'],
              sejarah: ['sejarah', 'asal', 'cerita', 'didirikan'],
              tanggal: ['besok', 'hari apa', 'tanggal'],
              lokasi: ['alamat', 'lokasi', 'dimana'],
              kontak: ['telepon', 'email', 'hubungi']
            };

            // Cari konteks pertanyaan
            const konteks = Object.keys(konteksPertanyaan).find(key => 
              konteksPertanyaan[key].some(kata => 
                messageText.toLowerCase().includes(kata)
              )
            );

            // Siapkan respons default
            let responsTambahan = 'Maaf, saya tidak dapat menemukan informasi spesifik untuk pertanyaan Anda.';

            // Generate respons berdasarkan konteks
            switch(konteks) {
              case 'identitas':
                responsTambahan = `Saya adalah asisten AI resmi FutZone Futsal Center. Saya dibuat untuk membantu Anda dengan informasi seputar lapangan futsal, booking, dan layanan kami.`;
                break;
              
              case 'sejarah':
                responsTambahan = FUTZONE_INFO.sejarah;
                break;
              
              case 'tanggal':
                const hariIni = new Date();
                const besok = new Date(hariIni);
                besok.setDate(hariIni.getDate() + 1);
                
                const namaHari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                responsTambahan = `Besok adalah hari ${namaHari[besok.getDay()]} tanggal ${besok.getDate()}`;
                break;
              
              case 'lokasi':
                responsTambahan = `Lokasi FutZone: ${FUTZONE_INFO.profil.lokasi}`;
                break;
              
              case 'kontak':
                responsTambahan = `Kontak FutZone:\n- Telepon: ${FUTZONE_INFO.profil.telepon}\n- Email: ${FUTZONE_INFO.profil.email}`;
                break;
            }

            const systemPrompt = `Anda adalah asisten AI resmi FutZone Futsal Center. 
            Berikan informasi akurat, ramah, dan membantu seputar penyewaan lapangan futsal.

            Informasi Utama FutZone:
            📍 Lokasi: ${FUTZONE_INFO.profil.lokasi}
            ☎️ Kontak: ${FUTZONE_INFO.profil.telepon}
            📧 Email: ${FUTZONE_INFO.profil.email}

            🏟️ Fasilitas Lapangan:
            - Total Lapangan: ${FUTZONE_INFO.fasilitas.totalLapangan}
            - Lapangan Standar: ${FUTZONE_INFO.fasilitas.standar}
            - Lapangan Rumput Vinyl: ${FUTZONE_INFO.fasilitas.rumputVinyl}

            Sejarah Singkat:
            ${FUTZONE_INFO.sejarah}

            Informasi Tambahan:
            ${responsTambahan}

            Pertanyaan Pengguna: ${messageText}

            Instruksi:
            - Jawab dengan ramah dan informatif
            - Fokus pada kebutuhan spesifik pengguna
            - Berikan solusi praktis
            - Gunakan bahasa Indonesia yang baik dan komunikatif`;
            
            const result = await model.generateContent(systemPrompt);
            const response = await result.response;
            return response.text();
          } catch (error) {
            console.error('Gemini AI Error:', error);
            return 'Maaf, sedang ada gangguan. Silakan coba lagi atau hubungi admin FutZone.';
          }
        }

        // Fungsi kirim pesan
        async function sendMessage() {
          const messageText = messageInput.value.trim();
          if (messageText === '') return;

          const timeString = getCurrentTime();
          
          // Tambahkan pesan pengguna
          const userMessageEl = document.createElement('div');
          userMessageEl.className = 'chat-message user-message';
          userMessageEl.innerHTML = `
            <div class="message-bubble">
              <p>${messageText}</p>
              <span class="message-time">${timeString}</span>
            </div>
          `;
          chatMessages.appendChild(userMessageEl);
          
          // Bersihkan input
          messageInput.value = '';
          
          // Scroll ke bawah
          chatMessages.scrollTop = chatMessages.scrollHeight;
          
          try {
            // Tampilkan indikator typing
            const typingIndicator = document.querySelector('.typing-indicator');
            typingIndicator.classList.add('active');
            
            // Generate respons AI
            const aiResponseText = await generateGeminiResponse(messageText);
            
            // Sembunyikan indikator typing
            typingIndicator.classList.remove('active');
            
            // Tambahkan respons bot
            const botMessageEl = document.createElement('div');
            botMessageEl.className = 'chat-message bot-message';
            
            const botMessageBubble = document.createElement('div');
            botMessageBubble.className = 'message-bubble';
            
            const botResponseText = document.createElement('p');
            const botTimeEl = document.createElement('span');
            botTimeEl.className = 'message-time';
            botTimeEl.textContent = timeString;
            
            botMessageBubble.appendChild(botResponseText);
            botMessageBubble.appendChild(botTimeEl);
            botMessageEl.appendChild(botMessageBubble);
            
            chatMessages.appendChild(botMessageEl);
            
            // Animasi teks respons
            await animateText(botResponseText, aiResponseText);
            
            // Scroll ke bawah
            chatMessages.scrollTop = chatMessages.scrollHeight;
          } catch (error) {
            console.error('Kesalahan:', error);
          }
        }

        // Event listener untuk tombol toggle chat
        toggleChatBtn.addEventListener('click', function() {
          console.log('Toggle chat clicked');
          chatContainer.classList.add('active');
          toggleChatBtn.style.display = 'none';
          
          if (!chatOpened) {
            animateText(welcomeMessage, welcomeText);
            chatOpened = true;
          }
        });

        // Event listener untuk tombol tutup
        closeBtn.addEventListener('click', function() {
          chatContainer.classList.remove('active');
          toggleChatBtn.style.display = 'flex';
        });

        // Event listener untuk tombol minimize
        minimizeBtn.addEventListener('click', function() {
          chatContainer.classList.remove('active');
          toggleChatBtn.style.display = 'flex';
        });

        // Kirim pesan saat tombol ditekan
        sendMessageBtn.addEventListener('click', sendMessage);

        // Kirim pesan saat Enter ditekan
        messageInput.addEventListener('keypress', function(e) {
          if (e.key === 'Enter') {
            sendMessage();
          }
        });

        // Quick reply buttons
        const quickReplyButtons = document.querySelectorAll('.quick-reply-btn');
        quickReplyButtons.forEach(button => {
          button.addEventListener('click', function() {
            const message = this.getAttribute('data-message');
            messageInput.value = message;
            sendMessage();
          });
        });

        // Tampilkan tombol toggle chat
        toggleChatBtn.style.display = 'flex';
      });
    </script>
    </body>
</html>