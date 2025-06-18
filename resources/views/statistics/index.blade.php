@extends('layouts.app')

@section('title', 'Thống kê')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold mb-4">Tổng quan</h1>
        
        <!-- Date Filter -->
        <form id="filterForm" class="bg-white p-4 rounded-lg shadow mb-6">
            <div class="flex gap-4">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700">Từ ngày</label>
                    <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700">Đến ngày</label>
                    <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Lọc
                    </button>
                </div>
            </div>
        </form>
        


        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <!-- Thu nhập -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-2">Tổng thu nhập</h3>
                <p class="text-2xl font-bold text-green-600">{{ number_format($totalIncomes) }} đ</p>
            </div>
            <!-- Chi tiêu -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-2">Tổng chi tiêu</h3>
                <p class="text-2xl font-bold text-red-600">{{ number_format($totalExpenses) }} đ</p>
            </div>
            <!-- Chênh lệch -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-2">Chênh lệch</h3>
                <p class="text-2xl font-bold {{ $balance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                    {{ number_format($balance) }} đ
                </p>
            </div>
        </div>


        
        <!-- Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Chi tiêu theo danh mục -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Chi tiêu theo danh mục</h3>
                <canvas id="expensesPieChart"></canvas>
            </div>
            
            <!-- Thu nhập theo danh mục -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Thu nhập theo danh mục</h3>
                <canvas id="incomesPieChart"></canvas>
            </div>

            <!-- Chi tiêu theo tháng -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Chi tiêu theo tháng</h3>
                <canvas id="expensesBarChart"></canvas>
            </div>

            <!-- Thu - Chi theo tháng -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Thu - Chi theo tháng</h3>
                <canvas id="balanceLineChart"></canvas>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Dữ liệu cho biểu đồ
const expensesByCategory = @json($expensesByCategory);
const incomesByCategory = @json($incomesByCategory);
const expensesByMonth = @json($expensesByMonth);
const incomesByMonth = @json($incomesByMonth);

// Hàm tạo màu ngẫu nhiên
function generateColors(count) {
    const colors = [];
    for (let i = 0; i < count; i++) {
        colors.push(`hsl(${(i * 360) / count}, 70%, 50%)`);
    }
    return colors;
}

// Biểu đồ tròn chi tiêu theo danh mục
new Chart(document.getElementById('expensesPieChart'), {
    type: 'pie',
    data: {
        labels: expensesByCategory.map(item => item.name),
        datasets: [{
            data: expensesByCategory.map(item => item.total),
            backgroundColor: generateColors(expensesByCategory.length),
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'right',
            }
        }
    }
});

// Biểu đồ tròn thu nhập theo danh mục
new Chart(document.getElementById('incomesPieChart'), {
    type: 'pie',
    data: {
        labels: incomesByCategory.map(item => item.name),
        datasets: [{
            data: incomesByCategory.map(item => item.total),
            backgroundColor: generateColors(incomesByCategory.length),
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'right',
            }
        }
    }
});

// Biểu đồ cột chi tiêu theo tháng
new Chart(document.getElementById('expensesBarChart'), {
    type: 'bar',
    data: {
        labels: Object.keys(expensesByMonth),
        datasets: [{
            label: 'Chi tiêu',
            data: Object.values(expensesByMonth),
            backgroundColor: 'rgba(239, 68, 68, 0.5)',
            borderColor: 'rgb(239, 68, 68)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Biểu đồ đường thu - chi theo tháng
new Chart(document.getElementById('balanceLineChart'), {
    type: 'line',
    data: {
        labels: Object.keys(expensesByMonth),
        datasets: [{
            label: 'Thu nhập',
            data: Object.values(incomesByMonth),
            borderColor: 'rgb(34, 197, 94)',
            backgroundColor: 'rgba(34, 197, 94, 0.5)',
            tension: 0.1
        }, {
            label: 'Chi tiêu',
            data: Object.values(expensesByMonth),
            borderColor: 'rgb(239, 68, 68)',
            backgroundColor: 'rgba(239, 68, 68, 0.5)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Form submit
document.getElementById('filterForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const params = new URLSearchParams(formData);
    window.location.href = `/statistics?${params.toString()}`;
});
</script>
@endpush