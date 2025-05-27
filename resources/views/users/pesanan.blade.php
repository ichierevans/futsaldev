@extends('users.layout')

@section('styles')
<style>
    .customer-tabs {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
    }

    .customer-tab {
        background-color: #ffc107;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        font-weight: bold;
    }

    .customer-tab.active {
        background-color: #28a745;
    }

    .order-status {
        background-color: #ffc107;
        color: white;
        padding: 10px 20px;
        border-radius: 5px;
        display: inline-block;
        font-weight: bold;
        margin-bottom: 20px;
    }

    .orders-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        background-color: white;
        border-radius: 10px;
        overflow: hidden;
    }

    .orders-table th, .orders-table td {
        border: 1px solid #dee2e6;
        padding: 12px;
        text-align: center;
    }

    .orders-table th {
        background-color: #f8f9fa;
        color: #333;
    }

    .note {
        color: #333;
        margin-top: 10px;
        margin-bottom: 20px;
    }

    .history-tab {
        background-color: #ffc107;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        font-weight: bold;
        margin-bottom: 20px;
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
    }

    .badge {
        display: inline-block;
        padding: 0.35em 0.65em;
        font-size: 0.75em;
        font-weight: 700;
        line-height: 1;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: 0.25rem;
    }

    .badge.bg-warning {
        color: #000;
        background-color: #ffc107;
    }

    .badge.bg-success {
        background-color: #28a745;
    }

    .badge.bg-danger {
        background-color: #dc3545;
    }

    .badge.bg-info {
        background-color: #17a2b8;
    }

    .badge.bg-secondary {
        background-color: #6c757d;
    }
</style>
@endsection

@section('content')
<h3 class="mb-3">Pesanan / Riwayat</h3>

<div class="customer-tabs">
    <button class="customer-tab active" data-type="reguler">CUSTOMER REGULER</button>
    <button class="customer-tab" data-type="membership">CUSTOMER MEMBERSHIP</button>
    <button class="customer-tab" data-type="event">CUSTOMER EVENT</button>
</div>

<div id="pending-orders" class="tab-content active">
    <div class="order-status">SEDANG DIPESAN</div>
    <div class="table-responsive">
        <table class="orders-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Tanggal</th>
                    <th>Lapangan</th>
                    <th>Durasi</th>
                    <th>Jam</th>
                    <th>Harga</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="pending-orders-body">
                <tr><td colspan="8" class="text-center">Memuat data...</td></tr>
            </tbody>
        </table>
    </div>
    <p class="note">NB: Harap tunggu validasi oleh admin max 1x24 jam, Setelah di konfirmasi oleh admin nama anda akan muncul di jadwal lapangan secara otomatis</p>
</div>

<div id="confirmed-orders" class="tab-content">
    <div class="order-status">TERKONFIRMASI</div>
    <div class="table-responsive">
        <table class="orders-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Tanggal</th>
                    <th>Lapangan</th>
                    <th>Durasi</th>
                    <th>Jam</th>
                    <th>Harga</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="confirmed-orders-body">
                <tr><td colspan="8" class="text-center">Memuat data...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<button class="history-tab" id="history-button">RIWAYAT</button> 

<div id="completed-orders" style="display: none;">
    <div class="order-status">RIWAYAT PESANAN DIBATALKAN</div>
    <div class="table-responsive">
        <table class="orders-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Tanggal</th>
                    <th>Lapangan</th>
                    <th>Durasi</th>
                    <th>Jam</th>
                    <th>Harga</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="cancelled-orders-body">
                <tr><td colspan="8" class="text-center">Memuat data...</td></tr>
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const customerTabs = document.querySelectorAll('.customer-tab');
    const historyButton = document.getElementById('history-button');
    const completedOrdersDiv = document.getElementById('completed-orders');

    let currentType = 'reguler';
    let orderData = {
        pendingOrders: [],
        confirmedOrders: [],
        cancelledOrders: []
    };

    function loadOrders(customerType) {
        // Show loading indicators
        ['pending-orders-body', 'confirmed-orders-body', 'cancelled-orders-body'].forEach(tableId => {
            document.getElementById(tableId).innerHTML = '<tr><td colspan="8" class="text-center">Memuat data...</td></tr>';
        });

        fetch(`/api/orders/${customerType}`)
            .then(res => {
                if (!res.ok) {
                    return res.json().then(errorData => {
                        throw new Error(errorData.message || 'Gagal memuat data');
                    }).catch(() => {
                        throw new Error(`HTTP error! status: ${res.status}`);
                    });
                }
                return res.json();
            })
            .then(data => {
                console.log('Received order data:', data);

                // Store the full data
                orderData = {
                    pendingOrders: data.pendingOrders || [],
                    confirmedOrders: data.confirmedOrders || [],
                    cancelledOrders: data.cancelledOrders || []
                };

                // Update tables
                updateTable('pending-orders-body', orderData.pendingOrders);
                updateTable('confirmed-orders-body', orderData.confirmedOrders);
                updateTable('cancelled-orders-body', orderData.cancelledOrders);
            })
            .catch(error => {
                console.error('Error loading orders:', error);
                
                ['pending-orders-body', 'confirmed-orders-body', 'cancelled-orders-body'].forEach(tableId => {
                    document.getElementById(tableId).innerHTML = 
                        `<tr>
                            <td colspan="8" class="text-center text-danger">
                                Gagal memuat data: ${error.message || 'Kesalahan tidak diketahui'}
                            </td>
                        </tr>`;
                });
            });
    }

    function updateTable(tableBodyId, orders) {
        const tableBody = document.getElementById(tableBodyId);
        
        if (orders.length === 0) {
            tableBody.innerHTML = 
                `<tr>
                    <td colspan="8" class="text-center text-muted">
                        Tidak ada pesanan
                    </td>
                </tr>`;
            return;
        }

        tableBody.innerHTML = orders.map((order, index) => 
            `<tr>
                <td>${index + 1}</td>
                <td>${order.user_name}</td>
                <td>${formatDate(order.booking_date)}</td>
                <td>${order.field_name}</td>
                <td>${order.duration} jam</td>
                <td>${order.start_time} - ${order.end_time}</td>
                <td>Rp ${order.total_price.toLocaleString('id-ID')}</td>
                <td>
                    <span class="badge ${getStatusBadgeClass(order.status)}">
                        ${formatOrderStatus(order.status)}
                    </span>
                </td>
            </tr>`
        ).join('');
    }

    function getStatusBadgeClass(status) {
        const statusClasses = {
            'pending': 'bg-warning',
            'waiting_confirmation': 'bg-warning',
            'confirmed': 'bg-success',
            'booking_confirmed': 'bg-success',
            'completed': 'bg-info',
            'cancelled': 'bg-danger'
        };
        return statusClasses[status] || 'bg-secondary';
    }

    function formatOrderStatus(status) {
        const statusMap = {
            'pending': 'Menunggu Konfirmasi',
            'waiting_confirmation': 'Menunggu Konfirmasi',
            'confirmed': 'Terkonfirmasi',
            'booking_confirmed': 'Terkonfirmasi',
            'completed': 'Selesai',
            'cancelled': 'Dibatalkan'
        };
        return statusMap[status] || status;
    }

    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('id-ID', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        });
    }

    // Initial load for reguler type
    loadOrders(currentType);

    // Tab switching
    customerTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            customerTabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            currentType = this.getAttribute('data-type');
            loadOrders(currentType);
        });
    });

    // History button toggle
    historyButton.addEventListener('click', function() {
        completedOrdersDiv.style.display = 
            completedOrdersDiv.style.display === 'none' ? 'block' : 'none';
    });
});
</script>
@endsection