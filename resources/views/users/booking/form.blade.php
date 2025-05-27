@extends('users.layout')

@section('styles')
    <style>
        /* Booking Form Container */
        .booking-form-container {
            max-width: 900px;
            margin: 40px auto;
            padding: 25px 30px;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .form-title {
            font-size: 28px;
            font-weight: bold;
            color: #2e7d32;
            margin-bottom: 25px;
            text-align: center;
        }

        .field-info {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
        }

        .field-image {
            width: 250px;
            height: auto;
            border-radius: 10px;
            object-fit: cover;
        }

        .field-details {
            flex: 1;
        }

        .field-details h3 {
            font-size: 22px;
            color: #333;
        }

        .field-details p {
            font-size: 16px;
            color: #555;
            margin: 5px 0;
        }

        .price-calculator {
            margin-top: 30px;
            background-color: #f4fef5;
            border: 1px solid #c8e6c9;
            padding: 20px;
            border-radius: 8px;
        }

        .price-calculator h4 {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 15px;
            color: #2e7d32;
        }

        .price-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 16px;
        }

        .price-total {
            font-weight: bold;
            border-top: 1px solid #ccc;
            padding-top: 10px;
            margin-top: 15px;
            font-size: 18px;
            color: #1b5e20;
        }

        .booking-notes {
            margin-top: 30px;
            background-color: #fffde7;
            border-left: 5px solid #fdd835;
            padding: 15px 20px;
            border-radius: 5px;
            font-size: 15px;
        }

        .booking-notes ul {
            padding-left: 20px;
            margin: 0;
        }

        .submit-btn {
            margin-top: 30px;
            width: 100%;
            padding: 12px;
            background-color: #43a047;
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .submit-btn:hover {
            background-color: #388e3c;
        }

        @media (max-width: 768px) {
            .field-info {
                flex-direction: column;
                align-items: center;
            }

            .field-image {
                width: 100%;
                height: auto;
            }

            .field-details {
                text-align: center;
                margin-top: 20px;
            }
        }

        .duration-slider-container {
            margin-top: 20px;
        }

        .form-range {
            width: 100%;
            height: 10px;
            background: #28a745;
            outline: none;
            opacity: 0.7;
            transition: opacity 0.2s;
            border-radius: 5px;
            -webkit-appearance: none;
        }

        .form-range:hover {
            opacity: 1;
        }

        .form-range::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 25px;
            height: 25px;
            background: #4CAF50;
            cursor: pointer;
            border-radius: 50%;
            border: 3px solid white;
            box-shadow: 0 4px 6px rgba(0,0,0,0.2);
        }

        .form-range::-moz-range-thumb {
            width: 25px;
            height: 25px;
            background: #4CAF50;
            cursor: pointer;
            border-radius: 50%;
            border: 3px solid white;
            box-shadow: 0 4px 6px rgba(0,0,0,0.2);
        }

        .duration-labels {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
            font-size: 14px;
            color: #666;
        }

        /* Horizontal Scrolling for Field Info */
        .field-info-container {
            display: flex;
            overflow-x: auto;
            scroll-behavior: smooth;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
            -ms-overflow-style: none;
            gap: 20px;
            padding-bottom: 20px;
            cursor: grab;
        }

        .field-info-container::-webkit-scrollbar {
            display: none;
        }

        .field-info-container.dragging {
            cursor: grabbing;
        }

        .field-info {
            display: flex;
            flex: 0 0 auto;
            width: 100%;
            max-width: 800px;
            gap: 20px;
            margin-bottom: 30px;
            transition: transform 0.3s ease;
        }

        .field-info-container .field-info:hover {
            transform: scale(1.02);
        }

        /* Scroll buttons */
        .scroll-controls {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }

        .scroll-btn {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .scroll-btn:hover {
            background-color: #218838;
        }

        @media (max-width: 768px) {
            .field-info {
                flex-direction: column;
                align-items: center;
                width: 100%;
                max-width: 100%;
            }
        }
    </style>
@endsection

@section('content')
<div class="booking-form-container">
    <h2 class="form-title">Form Pemesanan Lapangan Futsal</h2>

    <div class="field-info-container" id="fieldInfoContainer">
        <div class="field-info">
            <img src="{{ asset('storage/' . $field->image) }}" alt="Field Image" class="field-image">
            <div class="field-details">
                <h3>{{ $field->nama }}</h3>
                <p><strong>Harga Siang:</strong> Rp {{ number_format($field->harga_siang, 0, ',', '.') }}</p>
                <p><strong>Harga Malam:</strong> Rp {{ number_format($field->harga_malam, 0, ',', '.') }}</p>
                <p><strong>Lokasi:</strong> {{ $field->lokasi }}</p>
                <p><strong>Deskripsi:</strong> {{ $field->deskripsi }}</p>
            </div>
        </div>
        
        @if($similarFields->count() > 0)
            @foreach($similarFields as $similarField)
                <div class="field-info">
                    <img src="{{ asset('storage/' . $similarField->image) }}" alt="Field Image" class="field-image">
                    <div class="field-details">
                        <h3>{{ $similarField->nama }}</h3>
                        <p><strong>Harga Siang:</strong> Rp {{ number_format($similarField->harga_siang, 0, ',', '.') }}</p>
                        <p><strong>Harga Malam:</strong> Rp {{ number_format($similarField->harga_malam, 0, ',', '.') }}</p>
                        <p><strong>Lokasi:</strong> {{ $similarField->lokasi }}</p>
                        <p><strong>Deskripsi:</strong> {{ $similarField->deskripsi }}</p>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <div class="scroll-controls">
        <button type="button" class="scroll-btn" id="scrollLeftBtn">&larr; Kiri</button>
        <button type="button" class="scroll-btn" id="scrollRightBtn">Kanan &rarr;</button>
    </div>

    <div class="price-calculator">
        <h4>Perkiraan Harga</h4>
        <div class="price-row">
            <span>Durasi (jam):</span>
            <span id="duration-price">1</span>
        </div>
        <div class="price-row">
            <span>Total:</span>
            <span id="total-price">Rp {{ number_format($field->harga_siang, 0, ',', '.') }}</span>
        </div>
        <div class="price-total">
            <span>Total Pembayaran:</span>
            <span id="final-total">Rp {{ number_format($field->harga_siang, 0, ',', '.') }}</span>
        </div>
    </div>

    <form action="{{ route('booking.reguler.store') }}" method="POST">
        @csrf
        <input type="hidden" name="lapangan_id" value="{{ $field->id }}">
        <div class="form-group">
            <label for="duration">Pilih Durasi (jam):</label>
            <div class="duration-slider-container">
                <input type="range" id="duration" name="durasi" 
                       class="form-range" 
                       min="1" 
                       max="5" 
                       value="1" 
                       step="1">
                <div class="duration-labels">
                    <span>1 jam</span>
                    <span>2 jam</span>
                    <span>3 jam</span>
                    <span>4 jam</span>
                    <span>5 jam</span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <button type="submit" class="submit-btn">Konfirmasi Pembayaran</button>
        </div>
    </form>

    <div class="booking-notes">
        <h5>Catatan:</h5>
        <ul>
            <li>Pemesanan lapangan ini berlaku hanya pada waktu yang dipilih.</li>
            <li>Pastikan Anda memilih durasi yang sesuai dengan kebutuhan Anda.</li>
            <li>Harga malam berlaku mulai pukul 18:00.</li>
        </ul>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const durationInput = document.getElementById('duration');
    const totalPriceSpan = document.getElementById('total-price');
    const finalTotalSpan = document.getElementById('final-total');
    const durationPriceSpan = document.getElementById('duration-price');
    
    const hargaSiang = {{ $field->harga_siang }};
    const hargaMalam = {{ $field->harga_malam }};
    
    function updatePrice() {
        const duration = parseInt(durationInput.value);
        const currentHour = new Date().getHours();
        const basePrice = currentHour >= 18 ? hargaMalam : hargaSiang;
        const total = basePrice * duration;
        
        durationPriceSpan.textContent = duration;
        totalPriceSpan.textContent = 'Rp ' + total.toLocaleString('id-ID');
        finalTotalSpan.textContent = 'Rp ' + total.toLocaleString('id-ID');
    }
    
    durationInput.addEventListener('input', updatePrice);
    
    // Trigger initial price update
    updatePrice();

    // Horizontal Scrolling for Field Info
    const fieldInfoContainer = document.getElementById('fieldInfoContainer');
    const scrollLeftBtn = document.getElementById('scrollLeftBtn');
    const scrollRightBtn = document.getElementById('scrollRightBtn');

    // Drag scrolling
    let isDragging = false;
    let startX, scrollLeft;

    fieldInfoContainer.addEventListener('mousedown', (e) => {
        isDragging = true;
        fieldInfoContainer.classList.add('dragging');
        startX = e.pageX - fieldInfoContainer.offsetLeft;
        scrollLeft = fieldInfoContainer.scrollLeft;
    });

    fieldInfoContainer.addEventListener('mouseleave', () => {
        isDragging = false;
        fieldInfoContainer.classList.remove('dragging');
    });

    fieldInfoContainer.addEventListener('mouseup', () => {
        isDragging = false;
        fieldInfoContainer.classList.remove('dragging');
    });

    fieldInfoContainer.addEventListener('mousemove', (e) => {
        if (!isDragging) return;
        e.preventDefault();
        const x = e.pageX - fieldInfoContainer.offsetLeft;
        const walk = (x - startX) * 2;
        fieldInfoContainer.scrollLeft = scrollLeft - walk;
    });

    // Scroll buttons
    scrollLeftBtn.addEventListener('click', () => {
        fieldInfoContainer.scrollBy({
            left: -300,
            behavior: 'smooth'
        });
    });

    scrollRightBtn.addEventListener('click', () => {
        fieldInfoContainer.scrollBy({
            left: 300,
            behavior: 'smooth'
        });
    });

    // Touch support for mobile
    let touchStartX = 0;
    fieldInfoContainer.addEventListener('touchstart', (e) => {
        touchStartX = e.touches[0].clientX;
    });

    fieldInfoContainer.addEventListener('touchmove', (e) => {
        const touchMoveX = e.touches[0].clientX;
        const diff = touchStartX - touchMoveX;
        
        // Scroll the container
        fieldInfoContainer.scrollLeft += diff;
        
        // Update touch start position
        touchStartX = touchMoveX;
    });
});
</script>
@endsection
