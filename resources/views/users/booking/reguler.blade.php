@extends('users.layout')

@section('styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .field-container {
            display: flex;
            flex-wrap: nowrap;
            width: 100%;
            max-width: 100%;
            margin-top: 30px;
            overflow-x: auto;
            overflow-y: hidden;
            padding-bottom: 20px;
            scroll-snap-type: x mandatory;
            scrollbar-width: thin;
            scrollbar-color: #28a745 #f0f0f0;
            -webkit-overflow-scrolling: touch;
            position: relative;
            z-index: 10;
        }

        .field-container::-webkit-scrollbar {
            height: 8px;
        }

        .field-container::-webkit-scrollbar-track {
            background: #f0f0f0;
        }

        .field-container::-webkit-scrollbar-thumb {
            background-color: #28a745;
            border-radius: 4px;
        }

        .field-card {
            flex: 0 0 auto;
            width: 300px;
            min-width: 280px;
            scroll-snap-align: center;
            margin-right: 10px;
            background-color: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin-bottom: 30px;
            transition: transform 0.3s, box-shadow 0.3s;
            position: relative;
            z-index: 10;
            scroll-snap-align: center;
        }

        .field-card:hover {
            transform: scale(1.03);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .field-image {
            width: 100%;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .field-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #333;
        }

        .field-description {
            color: #666;
            font-size: 14px;
            margin-bottom: 20px;
            text-align: center;
        }

        .book-btn {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 8px 30px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .book-btn:hover {
            background-color: #218838;
        }

        .page-title {
            font-size: 26px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #333;
            position: relative;
            z-index: 10;
        }

        /* Modal Popup Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            overflow: auto;
        }

        .modal-content {
            position: relative;
            background-color: #28a745;
            margin: 10% auto;
            width: 80%;
            max-width: 650px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            animation: modalopen 0.4s;
        }

        @keyframes modalopen {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-header {
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .modal-header h2 {
            color: white;
            margin: 0;
            font-size: 24px;
        }

        .close-btn {
            color: white;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close-btn:hover {
            color: #ddd;
        }

        .modal-body {
            padding: 20px;
            color: white;
        }

        .booking-form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .booking-section {
            margin-bottom: 15px;
        }

        .booking-info {
            background: rgba(255, 255, 255, 0.1);
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .form-input {
            width: 100%;
            padding: 12px 15px;
            border: none;
            border-radius: 5px;
            background-color: white;
            font-size: 16px;
            margin-bottom: 10px;
        }

        .time-slots {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-top: 15px;
        }

        .time-slot {
            background-color: white;
            border: none;
            border-radius: 5px;
            padding: 10px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .time-slot:hover:not(:disabled) {
            background-color: #f0f0f0;
        }

        .time-slot.selected {
            background-color: #1e7e34;
            color: white;
        }

        .time-slot:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .next-btn {
            width: 100%;
            padding: 12px;
            background-color: white;
            color: #28a745;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 15px;
            transition: background-color 0.2s;
        }

        .next-btn:hover {
            background-color: #f0f0f0;
        }

        .back-btn {
            background-color: transparent;
            border: none;
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            padding: 0;
            font-size: 16px;
        }

        .back-btn svg {
            width: 20px;
            height: 20px;
            margin-right: 5px;
        }

        /* Payment Modal Styles */
        .payment-modal {
            display: none;
            position: fixed;
            z-index: 1100;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            overflow: auto;
        }

        .payment-modal-content {
            position: relative;
            background-color: white;
            margin: 10% auto;
            width: 80%;
            max-width: 500px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            animation: modalopen 0.4s;
        }

        .payment-modal-header {
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #eee;
        }

        .payment-modal-header h2 {
            color: #333;
            margin: 0;
            font-size: 24px;
        }

        .payment-modal-body {
            padding: 20px;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .submit-btn {
            width: 100%;
            padding: 12px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 16px;
            font-weight: bold;
        }

        .submit-btn:hover {
            background-color: #218838;
        }

        /* Success Modal Styles */
        .success-modal {
            display: none;
            position: fixed;
            z-index: 1200;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            overflow: auto;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .success-modal-content {
            background-color: white;
            border-radius: 10px;
            width: 90%;
            max-width: 500px;
            padding: 30px 20px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .success-icon {
            color: #28a745;
            font-size: 60px;
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .field-container {
                padding-left: 15px;
                padding-right: 15px;
            }

            .field-card {
                width: 250px;
                min-width: 250px;
            }

            .modal-content, .payment-modal-content {
                width: 95%;
                margin: 15% auto;
            }

            .time-slots {
                grid-template-columns: repeat(2, 1fr);
            }

            .field-container {
                padding-bottom: 30px;
            }

            .field-card {
                width: 280px;
                min-width: 280px;
            }
        }

        /* Optional: Add navigation buttons for better accessibility */
        .scroll-navigation {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }

        .scroll-btn {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .scroll-btn:hover {
            background-color: #218838;
        }

        @media (max-width: 480px) {
            .field-container {
                gap: 10px;
            }

            .field-card {
                width: 220px;
                min-width: 220px;
            }
        }
    </style>
@endsection

@section('content')
    <h1 class="page-title">Booking Lapangan</h1>

    <div class="field-container" id="fieldContainer">
        @foreach($fields as $field)
            <div class="field-card">
                <img src="{{ asset('storage/' . $field->image) }}" alt="{{ $field->nama }}" class="field-image">
                <h2 class="field-title">{{ $field->nama }}</h2>
                <p class="field-description">
                    {{ $field->deskripsi }}<br>
                    Harga Siang: Rp {{ number_format($field->harga_siang, 0, ',', '.') }}<br>
                    Harga Malam: Rp {{ number_format($field->harga_malam, 0, ',', '.') }}
                </p>
                <button class="book-btn" onclick="openBookingModal({{ $field->id }})">Book</button>
            </div>
        @endforeach
    </div>

    <!-- Booking Modal Popup -->
    <div id="bookingModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <button class="back-btn" onclick="closeBookingModal()">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 12H5M12 19l-7-7 7-7"></path>
                    </svg>
                </button>
                <h2 id="modalTitle">Booking Lapangan</h2>
                <span class="close-btn" onclick="closeBookingModal()">&times;</span>
            </div>
            <div class="modal-body">
                <div class="booking-info">
                    <div class="info-row">
                        <div>Nama Lengkap</div>
                        <div>{{ Auth::user()->name }}</div>
                    </div>
                    <div class="info-row">
                        <div>No.Hp/Whatsapp</div>
                        <div>{{ Auth::user()->phone }}</div>
                    </div>
                </div>

                <form class="booking-form" id="bookingForm">
                    @csrf
                    <input type="hidden" id="lapanganId" name="lapangan_id">

                    <div class="booking-section">
                        <select id="jenisBooking" name="jenis_booking" class="form-input" required>
                            <option value="" disabled selected>Jenis Booking</option>
                            <option value="reguler">Booking Reguler</option>
                            <option value="membership">Booking Membership</option>
                            <option value="event">Booking Event</option>
                        </select>
                    </div>

                    <div class="booking-section">
                        <input type="date" id="kalender" name="tanggal" class="form-input" 
                            min="{{ date('Y-m-d') }}" 
                            value="{{ date('Y-m-d') }}" 
                            required>
                    </div>

                    <div class="booking-section">
                        <select id="durasi" name="durasi" class="form-input" required>
                            <option value="" disabled selected>Durasi (Jam)</option>
                            <option value="1">1 Jam</option>
                            <option value="2">2 Jam</option>
                            <option value="3">3 Jam</option>
                            <option value="4">4 Jam</option>
                            <option value="5">5 Jam</option>
                        </select>
                    </div>

                    <div class="booking-section">
                        <div class="time-slots">
                            @for ($hour = 7; $hour <= 22; $hour++)
                                <button type="button" class="time-slot" data-time="{{ sprintf('%02d:00', $hour) }}">{{ sprintf('%02d.00', $hour) }}</button>
                            @endfor
                        </div>
                        <input type="hidden" id="selectedTime" name="waktu" required>
                    </div>

                    <button type="button" id="nextButton" class="next-btn">Selanjutnya</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    <div id="paymentModal" class="payment-modal">
        <div class="payment-modal-content">
            <div class="payment-modal-header">
                <button class="back-btn" onclick="closePaymentModal()" style="color: #333;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 12H5M12 19l-7-7 7-7"></path>
                    </svg>
                </button>
                <h2>Payment DP</h2>
                <span class="close-btn" onclick="closePaymentModal()" style="color: #333;">&times;</span>
            </div>
            <div class="payment-modal-body">
                <form id="paymentForm" class="booking-form" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label>Total Harga Lapangan</label>
                        <input type="text" class="form-control" id="totalPrice" readonly>
                    </div>
                    
                    <div class="form-group">
                        <label>DP (50%)</label>
                        <input type="text" class="form-control" id="dpAmount" readonly>
                    </div>
                    
                    <div class="form-group">
                        <label>Bank</label>
                        <select id="bankSelect" name="bank" class="form-control" required>
                            <option value="">Pilih Bank</option>
                            <option value="bri">Bank BRI 0090011065146501</option>
                            <option value="bca">Bank BCA 1210867658</option>
                            <option value="mandiri">Bank Mandiri 1430016340273</option>
                            <option value="dana">DANA 081913309344</option>
                            <option value="shopeepay">ShopeePay 081913309344</option>
                            
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Bukti Pembayaran DP</label>
                        <input type="file" id="paymentProof" name="payment_proof" class="form-control" accept="image/*" required>
                    </div>
                    
                    <button type="button" id="submitPayment" class="submit-btn">Submit Pembayaran DP</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div id="successModal" class="success-modal" style="display: none;">
        <div class="success-modal-content">
            <div class="success-icon">&#10004;</div>
            <h2>Pembayaran DP Berhasil!</h2>
            <p>Terima kasih telah melakukan pembayaran DP. Mohon tunggu konfirmasi selanjutnya.</p>
            <button class="submit-btn" onclick="closeSuccessModal()">Tutup</button>
        </div>
    </div>

    <script>
        // Get the modals
        const bookingModal = document.getElementById('bookingModal');
        const paymentModal = document.getElementById('paymentModal');
        const successModal = document.getElementById('successModal');
        const modalTitle = document.getElementById('modalTitle');
        const lapanganIdInput = document.getElementById('lapanganId');
        const timeSlots = document.querySelectorAll('.time-slot');
        const selectedTimeInput = document.getElementById('selectedTime');
        const durasiSelect = document.getElementById('durasi');
        const jenisBookingSelect = document.getElementById('jenisBooking');
        const nextButton = document.getElementById('nextButton');
        const submitPaymentButton = document.getElementById('submitPayment');

        // Durasi options berdasarkan jenis booking
        const durasiOptions = {
            reguler: [1, 2, 3, 4, 5],
            membership: [1, 2, 3, 4, 5],
            event: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]
        };

        // Function to update price based on booking type and duration
        function updatePrice() {
            const durasi = parseInt(durasiSelect.value) || 0;
            let basePrice = 100000; // Base price per hour
            
            // Adjust price based on booking type
            if (jenisBookingSelect.value === 'membership') {
                basePrice = 80000; // 20% discount for membership
            } else if (jenisBookingSelect.value === 'event') {
                basePrice = 90000; // 10% discount for events
            }
            
            const totalPrice = basePrice * durasi;
            const dpAmount = totalPrice * 0.5; // 50% DP
            
            // Format prices with Indonesian Rupiah format
            document.getElementById('totalPrice').value = `Rp ${totalPrice.toLocaleString('id-ID')},00`;
            document.getElementById('dpAmount').value = `Rp ${dpAmount.toLocaleString('id-ID')},00`;
        }
        

        // Function to update durasi options based on jenis booking
        function updateDurasiOptions() {
            const jenisBooking = jenisBookingSelect.value;
            const durasiOptionsForType = durasiOptions[jenisBooking] || [];

            // Clear existing options
            durasiSelect.innerHTML = '<option value="" disabled selected>Durasi (Jam)</option>';

            // Add new options
            durasiOptionsForType.forEach(durasi => {
                const option = document.createElement('option');
                option.value = durasi;
                option.textContent = `${durasi} Jam`;
                durasiSelect.appendChild(option);
            });

            // Reset time slots when jenis booking changes
            resetTimeSlots();
        }

        // Function to open the booking modal
        function openBookingModal(lapanganId) {
            modalTitle.textContent = `Booking Lapangan ${lapanganId}`;
            document.getElementById('lapanganId').value = lapanganId;
            bookingModal.style.display = 'block';

            // Set current date as default
            const today = new Date();
            const dateString = today.toISOString().split('T')[0];
            document.getElementById('kalender').value = dateString;
            document.getElementById('kalender').min = dateString;

            // Reset form when opening modal
            resetForm();
        }

        // Function to close the booking modal
        function closeBookingModal() {
            bookingModal.style.display = 'none';
            resetForm();
        }

        // Function to open the payment modal
        function openPaymentModal() {
            // Validate booking form first
            if (!validateBookingForm()) {
                return;
            }
            
            // Update prices based on selection
            updatePrice();
            
            // Hide booking modal and show payment modal
            bookingModal.style.display = 'none';
            paymentModal.style.display = 'block';
        }

        // Function to close the payment modal
        function closePaymentModal() {
            paymentModal.style.display = 'none';
            bookingModal.style.display = 'block'; // Go back to booking modal
        }

        // Function to validate booking form
        function validateBookingForm() {
            const lapanganId = document.getElementById('lapanganId').value;
            const jenisBooking = document.getElementById('jenisBooking').value;
            const tanggal = document.getElementById('kalender').value;
            const waktu = document.getElementById('selectedTime').value;
            const durasi = document.getElementById('durasi').value;
            
            if (!lapanganId) {
                alert('Silakan pilih lapangan terlebih dahulu.');
                return false;
            }
            
            if (!jenisBooking) {
                alert('Silakan pilih jenis booking.');
                return false;
            }
            
            if (!tanggal) {
                alert('Silakan pilih tanggal.');
                return false;
            }
            
            if (!waktu) {
                alert('Silakan pilih waktu.');
                return false;
            }
            
            if (!durasi) {
                alert('Silakan pilih durasi.');
                return false;
            }
            
            return true;
        }

        // Function to validate payment form
        function validatePaymentForm() {
            const bank = document.getElementById('bankSelect').value;
            const paymentProof = document.getElementById('paymentProof').files[0];
            
            if (!bank) {
                alert('Silakan pilih bank.');
                return false;
            }
            
            if (!paymentProof) {
                alert('Silakan unggah bukti pembayaran.');
                return false;
            }
            
            return true;
        }

        // Function to handle payment submission
        function submitPayment() {
            if (!validatePaymentForm()) {
                return;
            }
            
            // Hide payment modal and show success modal
            paymentModal.style.display = 'none';
            successModal.style.display = 'flex';
        }

        // Function to close success modal
        function closeSuccessModal() {
            successModal.style.display = 'none';
            // You can redirect here if needed
            // window.location.href = '/booking-confirmation';
        }

        // Reset all time slots to initial state
        let selectedSlotIndex = null;

        function resetTimeSlots() {
            timeSlots.forEach(slot => {
                slot.disabled = false;
                slot.classList.remove('selected');
                slot.style.opacity = '1';
                slot.style.cursor = 'pointer';
            });
            selectedTimeInput.value = '';
            selectedSlotIndex = null;
        }

        // Event listener for jenis booking change
        jenisBookingSelect.addEventListener('change', function () {
            updateDurasiOptions();
            resetTimeSlots();
        });

        // Event listener for durasi change
        durasiSelect.addEventListener('change', resetTimeSlots);

        // Time slot selection logic
        timeSlots.forEach((slot, index) => {
            slot.addEventListener('click', () => {
                const durasi = parseInt(durasiSelect.value);

                if (!durasi) {
                    alert('Silakan pilih durasi terlebih dahulu.');
                    return;
                }

                // Reset semua slot dulu
                resetTimeSlots();

                // Simpan waktu yang dipilih
                selectedSlotIndex = index;
                selectedTimeInput.value = slot.dataset.time;

                // Tambahkan highlight pada waktu mulai
                slot.classList.add('selected');

                // Nonaktifkan slot sesuai durasi
                for (let i = 0; i < durasi; i++) {
                    if (timeSlots[index + i]) {
                        timeSlots[index + i].disabled = true;
                        timeSlots[index + i].style.opacity = '0.5';
                        timeSlots[index + i].style.cursor = 'not-allowed';
                    }
                }

                // Aktifkan kembali slot yang diklik agar tetap disorot
                slot.disabled = false;
                slot.style.opacity = '1';
                slot.style.cursor = 'pointer';
            });
        });

        // Function to reset form
        function resetForm() {
            document.getElementById('bookingForm').reset();
            document.getElementById('paymentForm').reset();
            resetTimeSlots();
            // Set default jenis booking to reguler
            jenisBookingSelect.value = '';
            // Update durasi options
            updateDurasiOptions();
        }

        // Close modals if user clicks outside of them
        window.onclick = function (event) {
            if (event.target == bookingModal) {
                closeBookingModal();
            }
            if (event.target == paymentModal) {
                closePaymentModal();
            }
            if (event.target == successModal) {
                closeSuccessModal();
            }
        }

        // Add event listeners for buttons
        nextButton.addEventListener('click', async function() {
            try {
                // Validate booking form first
                if (!validateBookingForm()) {
                    return;
                }

                // Get form data
                const form = document.getElementById('bookingForm');
                const formData = new FormData(form);
                
                // Log form data for debugging
                console.log('Form Data:');
                for (let pair of formData.entries()) {
                    console.log(pair[0] + ': ' + pair[1]);
                }

                // Ensure hidden input values are set
                const lapanganId = document.getElementById('lapanganId').value;
                const selectedTime = document.getElementById('selectedTime').value;
                if (!lapanganId || !selectedTime) {
                    throw new Error('Data booking tidak lengkap. Silakan pilih lapangan dan waktu.');
                }
                
                // Send AJAX request
                const response = await fetch('{{ route("user.booking.process") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();
                
                // Log response for debugging
                console.log('Response:', {
                    status: response.status,
                    ok: response.ok,
                    data: data
                });

                // Only throw error if response is not ok
                if (!response.ok) {
                    throw new Error(data.message || 'Terjadi kesalahan saat memproses booking.');
                }

                // Process the successful response
                if (data.success) {
                    console.log('Booking successful, showing payment modal');
                    // Hide booking modal and show payment modal
                    bookingModal.style.display = 'none';
                    paymentModal.style.display = 'block';
                    
                    // Update price displays using raw values and toLocaleString
                    document.getElementById('totalPrice').value = 'Rp ' + data.total_price_raw.toLocaleString('id-ID') + ',00';
                    document.getElementById('dpAmount').value = 'Rp ' + data.dp_amount_raw.toLocaleString('id-ID') + ',00';
                } else {
                    console.log('Booking failed:', data.message);
                    alert(data.message || 'Terjadi kesalahan saat memproses booking.');
                }
            } catch (error) {
                console.error('Error in booking process:', error);
                alert(error.message || 'Terjadi kesalahan saat menghubungi server. Silakan coba lagi.');
            }
        });

        submitPaymentButton.addEventListener('click', async function() {
            try {
                if (!validatePaymentForm()) {
                    return;
                }

                const form = document.getElementById('paymentForm');
                const formData = new FormData(form);
                
                // Ensure we have the file
                const paymentProofFile = document.getElementById('paymentProof').files[0];
                if (!paymentProofFile) {
                    throw new Error('Silakan pilih file bukti pembayaran.');
                }
                
                // Add file to form data
                formData.append('payment_proof', paymentProofFile);
                
                const response = await fetch('{{ route("user.booking.payment") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin'
                });

                const data = await response.json();

                // Log full response for debugging
                console.log('Payment Response:', {
                    status: response.status,
                    ok: response.ok,
                    data: data
                });

                if (!response.ok) {
                    // Handle validation errors or other error responses
                    const errorMessage = data.errors 
                        ? Object.values(data.errors).flat().join('\n') 
                        : (data.message || 'Terjadi kesalahan saat memproses pembayaran.');
                    throw new Error(errorMessage);
                }

                if (data.success) {
                    // Hide payment modal and show success modal
                    paymentModal.style.display = 'none';
                    successModal.style.display = 'flex';
                    
                    // Clear the form
                    form.reset();
                    
                    // Optional: Redirect after a delay
                    setTimeout(() => {
                        window.location.href = '{{ route("user.pesanan") }}';
                    }, 3000);
                } else {
                    throw new Error(data.message || 'Terjadi kesalahan saat memproses pembayaran.');
                }
            } catch (error) {
                console.error('Payment Submission Error:', error);
                
                // Display error message to user
                alert(error.message || 'Terjadi kesalahan saat memproses pembayaran. Silakan coba lagi.');
                
                // Optionally, re-enable the submit button
                submitPaymentButton.disabled = false;
            }
        });

        // Initialize form when page loads
        document.addEventListener('DOMContentLoaded', function () {
            updateDurasiOptions();
        });

        document.addEventListener('DOMContentLoaded', function() {
            const fieldContainer = document.getElementById('fieldContainer');

            // Cursor-based scrolling
            let isDown = false;
            let startX;
            let scrollLeft;

            fieldContainer.addEventListener('mousedown', (e) => {
                isDown = true;
                fieldContainer.classList.add('active');
                startX = e.pageX - fieldContainer.offsetLeft;
                scrollLeft = fieldContainer.scrollLeft;
            });

            fieldContainer.addEventListener('mouseleave', () => {
                isDown = false;
                fieldContainer.classList.remove('active');
            });

            fieldContainer.addEventListener('mouseup', () => {
                isDown = false;
                fieldContainer.classList.remove('active');
            });

            fieldContainer.addEventListener('mousemove', (e) => {
                if (!isDown) return;
                e.preventDefault();
                const x = e.pageX - fieldContainer.offsetLeft;
                const walk = (x - startX) * 2; // Multiply by 2 to increase scroll speed
                fieldContainer.scrollLeft = scrollLeft - walk;
            });

            // Touch support for mobile devices
            fieldContainer.addEventListener('touchstart', (e) => {
                startX = e.touches[0].pageX - fieldContainer.offsetLeft;
                scrollLeft = fieldContainer.scrollLeft;
            });

            fieldContainer.addEventListener('touchmove', (e) => {
                const x = e.touches[0].pageX - fieldContainer.offsetLeft;
                const walk = (x - startX) * 2;
                fieldContainer.scrollLeft = scrollLeft - walk;
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const paymentModal = document.getElementById('paymentModal');
            const totalPriceDisplay = document.getElementById('totalPrice');
            const dpAmountDisplay = document.getElementById('dpAmount');
            const paymentForm = document.getElementById('paymentForm');

            // Function to format currency
            function formatCurrency(amount) {
                return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
            }

            // When booking is successful, populate payment modal
            window.populatePaymentModal = function(totalPrice, dpAmount) {
                totalPriceDisplay.value = formatCurrency(totalPrice);
                dpAmountDisplay.value = formatCurrency(dpAmount);
            }

            // Payment form submission
            paymentForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);

                fetch('{{ route('user.booking.payment') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Pembayaran Berhasil',
                            text: data.message,
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Pembayaran Gagal',
                            text: data.message,
                            confirmButtonText: 'Tutup'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Kesalahan',
                        text: 'Terjadi kesalahan saat memproses pembayaran',
                        confirmButtonText: 'Tutup'
                    });
                });
            });
        });
    </script>
@endsection