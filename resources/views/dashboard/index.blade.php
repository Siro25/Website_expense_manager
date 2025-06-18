@extends('layouts.app')

@section('title', 'Tổng quan')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold mb-4">Tổng quan</h1>
        
        <!-- Top Expense Categories -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Top danh mục chi tiêu</h3>
                <form action="{{ route('dashboard') }}" method="GET" class="flex items-center">
                    <select name="month" id="month-select" class="rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 text-sm mr-2" onchange="this.form.submit()">
                        @foreach($availableMonths as $availableMonth)
                            @php
                                $formattedMonth = \Carbon\Carbon::createFromFormat('Y-m', $availableMonth)->format('m/Y');
                            @endphp
                            <option value="{{ $availableMonth }}" {{ $month == $availableMonth ? 'selected' : '' }}>{{ $formattedMonth }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white text-sm py-1 px-2 rounded">
                        Lọc
                    </button>
                </form>
            </div>
            <div class="space-y-4">
                @forelse($topExpenseCategories as $category)
                    <div class="flex justify-between items-center">
                        <div class="flex items-center">
                            <div class="w-3 h-3 rounded-full mr-2" style="background-color: hsl({{ $loop->index * 120 }}, 70%, 50%)"></div>
                            <span>{{ $category['name'] }}</span>
                        </div>
                        <span class="font-semibold text-red-600">{{ number_format($category['total']) }} đ</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-red-600 h-2.5 rounded-full" style="width: {{ $topExpenseCategories->max('total') > 0 ? ($category['total'] / $topExpenseCategories->max('total')) * 100 : 0 }}%"></div>
                    </div>
                @empty
                    <p class="text-gray-500">Không có dữ liệu chi tiêu trong tháng {{ \Carbon\Carbon::createFromFormat('Y-m', $month)->format('m/Y') }}</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Chi tiêu gần đây -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Chi tiêu gần đây</h3>
                <div class="space-y-4">
                    @forelse($recentExpenses as $expense)
                        <div class="flex justify-between items-center border-b pb-2">
                            <div>
                                <p class="font-medium">{{ $expense->description }}</p>
                                <p class="text-sm text-gray-500">{{ $expense->category->name }} - {{ $expense->date->format('d/m/Y') }}</p>
                            </div>
                            <span class="font-semibold text-red-600">{{ number_format($expense->amount) }} đ</span>
                        </div>
                    @empty
                        <p class="text-gray-500">Không có dữ liệu chi tiêu gần đây</p>
                    @endforelse
                </div>
            </div>
            
            <!-- Thu nhập gần đây -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Thu nhập gần đây</h3>
                <div class="space-y-4">
                    @forelse($recentIncomes as $income)
                        <div class="flex justify-between items-center border-b pb-2">
                            <div>
                                <p class="font-medium">{{ $income->description }}</p>
                                <p class="text-sm text-gray-500">{{ $income->category->name }} - {{ $income->date->format('d/m/Y') }}</p>
                            </div>
                            <span class="font-semibold text-green-600">{{ number_format($income->amount) }} đ</span>
                        </div>
                    @empty
                        <p class="text-gray-500">Không có dữ liệu thu nhập gần đây</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection