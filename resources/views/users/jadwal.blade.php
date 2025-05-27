@extends('users.layout')

@section('styles')
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
    
    .calendar-header h4 {
        margin: 0;
        font-weight: bold;
    }

    .field-schedule {
        width: 100%;
        min-width: 1200px; /* Ensure horizontal scroll */
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

    .field-name.header-corner {
        z-index: 30;
    }
    
    .booking-cell {
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .booking-cell.available:hover {
        background-color: rgba(40, 167, 69, 0.1);
        transform: scale(1.02);
    }
    
    .booked {
        background-color: #dc3545;
        color: white;
        font-size: 12px;
        padding: 8px;
        border-radius: 4px;
    }
    
    .available {
        background-color: #ffffff;
    }
    
    .filter-date {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    
    .date-picker {
        padding: 8px 15px;
        border: 2px solid #ffffff;
        border-radius: 5px;
        background-color: rgba(255, 255, 255, 0.1);
        color: white;
        font-weight: bold;
    }

    .date-picker::-webkit-calendar-picker-indicator {
        filter: invert(1);
    }

    .status-legend {
        margin: 0 0 20px;
        display: flex;
        gap: 15px;
        align-items: center;
        padding: 10px 15px;
        background-color: #f8f9fa;
        border-radius: 10px;
    }

    .status-label {
        display: inline-flex;
        align-items: center;
        padding: 6px 12px;
        font-size: 13px;
        font-weight: 500;
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

    .booker-name {
        font-size: 12px;
        font-weight: bold;
        margin-bottom: 4px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        color: white;
    }

    .booking-time {
        font-size: 11px;
        opacity: 0.9;
        color: white;
    }

    .table-responsive {
        width: 100%;
        overflow-x: auto;
        overflow-y: hidden;
        position: relative;
        max-height: 700px;
        border-radius: 10px;
        border: 1px solid #e0e0e0;
    }

    .field-schedule thead {
        position: sticky;
        top: 0;
        z-index: 20;
    }

    .field-schedule thead th:first-child {
        z-index: 30;
    }

    /* Scrollbar styling */
    .table-responsive::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    .table-responsive::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }

    .table-responsive::-webkit-scrollbar-thumb {
        background: #28a745;
        border-radius: 4px;
    }

    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: #218838;
    }

    @media (max-width: 768px) {
        .calendar-header {
            flex-direction: column;
            gap: 10px;
        }

        .filter-date {
            width: 100%;
            justify-content: center;
        }

        .field-schedule {
            min-width: 1000px;
        }
    }
</style>
@endsection

@section('content')
<div class="calendar-container">
    <div class="calendar-header">
        <h4>Jadwal Lapangan</h4>
        <div class="filter-date">
            <label>Pilih Tanggal:</label>
            <input type="date" class="date-picker" id="date-picker" value="{{ $date }}">
        </div>
    </div>

    <div class="status-legend">
        <div class="status-label available-label">
            <span>✓ Tersedia</span>
        </div>
        <div class="status-label booked-label">
            <span>✕ Terisi</span>
        </div>
    </div>
    
    <div class="table-responsive">
        <table class="field-schedule">
            <thead>
                <tr>
                    <th class="field-name header-corner">Lapangan</th>
                    @for ($j = 7; $j <= 22; $j++)
                        <th>{{ sprintf("%02d", $j) }}:00</th>
                    @endfor
                </tr>
            </thead>
            <tbody>
                @foreach($fields as $field)
                <tr>
                    <td class="field-name">{{ $field->nama }}</td>
                    @for ($hour = 7; $hour <= 22; $hour++)
                        @php
                            $booking = $bookings->first(function($booking) use ($field, $hour) {
                                $startHour = (int)date('H', strtotime($booking->jam_mulai));
                                $endHour = (int)date('H', strtotime($booking->jam_selesai));
                                return $booking->lapangan_id == $field->id && 
                                       $hour >= $startHour && 
                                       $hour < $endHour;
                            });
                            $isBooked = $booking !== null;
                        @endphp
                        <td class="booking-cell {{ $isBooked ? 'booked' : 'available' }}" 
                            data-field="{{ $field->id }}" 
                            data-time="{{ $hour }}"
                            @if($isBooked) title="{{ $booking->user->name }} ({{ date('H:i', strtotime($booking->jam_mulai)) }} - {{ date('H:i', strtotime($booking->jam_selesai)) }})" @endif>
                            @if($isBooked)
                                <div class="booker-name">{{ $booking->user->name }}</div>
                                <div class="booking-time">
                                    {{ date('H:i', strtotime($booking->jam_mulai)) }} - 
                                    {{ date('H:i', strtotime($booking->jam_selesai)) }}
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
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const datePicker = document.getElementById('date-picker');
        
        datePicker.addEventListener('change', function() {
            const selectedDate = this.value;
            window.location.href = '{{ route("user.jadwal") }}?date=' + selectedDate;
        });
        
        const bookingCells = document.querySelectorAll('.booking-cell.available');
        bookingCells.forEach(cell => {
            cell.addEventListener('click', function() {
                const field = this.getAttribute('data-field');
                const time = this.getAttribute('data-time');
                const date = datePicker.value;
                window.location.href = '{{ route("user.booking.create") }}?field=' + field + '&time=' + time + '&date=' + date;
            });
        });
    });
</script>
@endsection
