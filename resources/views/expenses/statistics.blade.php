@extends('layouts.app')

@section('title', 'Thống Kê Chi Tiêu')

@section('content')
<div class="space-y-6">
    <!-- Bộ lọc thời gian -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('expenses.statistics') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700">Từ ngày</label>
                <input type="date" name="start_date" id="start_date"
                    value="{{ request('start_date', now()->startOfMonth()->format('Y-m-d')) }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700">Đến ngày</label>
                <input type="date" name="end_date" id="end_date"
                    value="{{ request('end_date', now()->endOfMonth()->format('Y-m-d')) }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div class="md:col-span-2 flex justify-end">
                <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
                    <i class="fas fa-sync-alt mr-2"></i>Cập nhật
                </button>
            </div>
        </form>
    </div>

    <!-- Tổng quan -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Tổng Chi Tiêu</h3>
            <p class="text-3xl font-bold text-blue-600">{{ number_format($total, 0, ',', '.') }} đ</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Trung Bình Mỗi Ngày</h3>
            <p class="text-3xl font-bold text-green-600">
                {{ number_format($total / max(1, count($daily)), 0, ',', '.') }} đ
            </p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Số Ngày Chi Tiêu</h3>
            <p class="text-3xl font-bold text-purple-600">{{ count($daily) }} ngày</p>
        </div>
    </div>

    <!-- Biểu đồ chi tiêu theo danh mục -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Chi Tiêu Theo Danh Mục</h3>
        <div class="h-80">
            <canvas id="categoryChart"></canvas>
        </div>
    </div>

    <!-- Biểu đồ chi tiêu theo ngày -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Chi Tiêu Theo Ngày</h3>
        <div class="h-80">
            <canvas id="dailyChart"></canvas>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Dữ liệu cho biểu đồ danh mục
    const categoryData = @json($byCategory);
    new Chart(document.getElementById('categoryChart'), {
        type: 'pie',
        data: {
            labels: categoryData.map(item => item.category.name),
            datasets: [{
                data: categoryData.map(item => item.total),
                backgroundColor: [
                    '#3B82F6', '#10B981', '#F59E0B', '#EF4444',
                    '#6366F1', '#EC4899', '#8B5CF6', '#6B7280'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right'
                }
            }
        }
    });

    // Dữ liệu cho biểu đồ theo ngày
    const dailyData = @json($daily);
    new Chart(document.getElementById('dailyChart'), {
        type: 'line',
        data: {
            labels: dailyData.map(item => {
                const date = new Date(item.date);
                return new Intl.DateTimeFormat('vi-VN', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                }).format(date);
            }),
            datasets: [{
                label: 'Chi tiêu',
                data: dailyData.map(item => item.total),
                borderColor: '#3B82F6',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return new Intl.NumberFormat('vi-VN', {
                                style: 'currency',
                                currency: 'VND'
                            }).format(value);
                        }
                    }
                }
            }
        }
    });
</script>
@endpush
@endsection 