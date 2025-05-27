@extends('users.layout')

@section('styles')
<style>
    .booking-container {
        background-color: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        position: relative;
        z-index: 2;
        max-width: 800px;
        margin: 0 auto;
    }
    
    .booking-heading {
        font-size: 28px;
        font-weight: bold;
        margin-bottom: 30px;
        color: #333;
    }
    
    .booking-field {
        margin-bottom: 20px;
    }
    
    .booking-label {
        font-size: 16px;
        color: #333;
        margin-bottom: 8px;
        display: block;
        font-weight: 500;
    }
    
    .booking-input {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ced4da;
        border-radius: 5px;
        background-color: #f8f9fa;
    }
    
    .booking-select {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ced4da;
        border-radius: 5px;
        background-color: #f8f9fa;
    }
    
    .booking-summary {
        background-color: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        margin-top: 30px;
        margin-bottom: 30px;
    }
    
    .summary-title {
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 15px;
        color: #333;
    }
    
    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
    }
    
    .summary-label {
        font-weight: 500;
        color: #333;
    }
    
    .summary-value {
        font-weight: 600;
        color: #28a745;
    }
    
    .total-row {
        display: flex;
        justify-content: space-between;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid #ced4da;
    }
    
    .total-label {
        font-weight: 600;
        color: #333;
        font-size: 18px;
    }
    
    .total-value {
        font-weight: 700;
        color: #28a745;
        font-size: 18px;
    }
    
    .booking-button {
        width: 100%;
        padding: 12px 15px;
        background-color: #28a745;
        color: white;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        font-weight: 500;
        cursor: pointer;
    }
    
    .booking-button:hover {
        background-color: #218838;
    }
</style>
@endsection

@section('content')
<div class="booking-container">
    <div class="booking-heading">Booking Lapangan</div>
    
    <form method="POST" action="{{ route('user.booking.store') }}">
        @csrf
        
        <div class="booking-field">
            <label class="booking-label">Lapangan</label>
            <select name="field_id" class="booking-select" required>
                <option value="" disabled>Pilih Lapangan</option>
                @for ($i = 1; $i <= 7; $i++)
                    <option value="{{ $i }}" {{ request('field') == $i ? 'selected' : '' }}>Lapangan {{ $i }}</option>
                @endfor
            </select>
        </div>
        
        <div class="booking-field">
            <label class="booking-label">Tanggal</label>
            <input type="date" name="date" class="booking-input" value="{{ request('date', date('Y-m-d')) }}" required>
        </div>
        
        <div class="booking-field">
            <label class="booking-label">Jam Mulai</label>
            <select name="time_start" id="time-start" class="booking-select" required>
                <option value="" disabled>Pilih Jam Mulai</option>
                @for ($i = 8; $i <= 17; $i++)
                    <option value="{{ $i }}.00" {{ request('time') == $i ? 'selected' : '' }}>{{ $i }}.00</option>
                @endfor
            </select>
        </div>
        
        <div class="booking-field">
            <label class="booking-label">Durasi (jam)</label>
            <select name="duration" id="duration" class="booking-select" required>
                <option value="" disabled>Pilih Durasi</option>
                @for ($i = 1; $i <= 5; $i++)
                    <option value="{{ $i }}">{{ $i }} jam</option>
                @endfor
            </select>
        </div>
        
        <div class="booking-summary">
            <div class="summary-title">Ringkasan Booking</div>
            
            <div class="summary-row">
                <div class="summary-label">Lapangan</div>
                <div class="summary-value" id="summary-field">-</div>
            </div>
            
            <div class="summary-row">
                <div class="summary-label">Tanggal</div>
                <div class="summary-value" id="summary-date">-</div>
            </div>
            
            <div class="summary-row">
                <div class="summary-label">Jam</div>
                <div class="summary-value" id="summary-time">-</div>
            </div>
            
            <div class="summary-row">
                <div class="summary-label">Durasi</div>
                <div class="summary-value" id="summary-duration">-</div>
            </div>
            
            <div class="total-row">
                <div class="total-label">Total</div>
                <div class="total-value" id="summary-total">Rp 0</div>
            </div>
        </div>
        
        <button type="submit" class="booking-button">Booking Sekarang</button>
    </form>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Function to update booking summary
        function updateSummary() {
            const fieldSelect = document.querySelector('select[name="field_id"]');
            const dateInput = document.querySelector('input[name="date"]');
            const timeStartSelect = document.querySelector('select[name="time_start"]');
            const durationSelect = document.querySelector('select[name="duration"]');
            
            const fieldId = fieldSelect.value;
            const date = dateInput.value;
            const timeStart = timeStartSelect.value;
            const duration = durationSelect.value;
            
            // Calculate end time
            let endHour = 0;
            if (timeStart && duration) {
                endHour = parseInt(timeStart) + parseInt(duration);
                if (endHour > 24) {
                    endHour = 24;
                }
            }
            
            // Format date for display
            let formattedDate = '-';
            if (date) {
                const dateObj = new Date(date);
                formattedDate = dateObj.toLocaleDateString('id-ID', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                });
            }
            
            // Calculate price (example calculation)
            let price = 0;
            if (duration) {
                // Base price per hour
                const hourlyRate = 150000;
                price = hourlyRate * parseInt(duration);
            }
            
            // Update summary elements
            document.getElementById('summary-field').textContent = fieldId ? `Lapangan ${fieldId}` : '-';
            document.getElementById('summary-date').textContent = formattedDate;
            document.getElementById('summary-time').textContent = timeStart && duration ? `${timeStart} - ${endHour}.00` : '-';
            document.getElementById('summary-duration').textContent = duration ? `${duration} jam` : '-';
            document.getElementById('summary-total').textContent = price ? `Rp ${price.toLocaleString('id-ID')}` : 'Rp 0';
        }
        
        // Add event listeners to form inputs
        const formInputs = document.querySelectorAll('select[name="field_id"], input[name="date"], select[name="time_start"], select[name="duration"]');
        formInputs.forEach(input => {
            input.addEventListener('change', updateSummary);
        });
        
        // Initial update
        updateSummary();
    });
</script>
@endsection