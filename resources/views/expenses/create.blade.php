@extends('layouts.app')

@section('title', 'Thêm Chi Tiêu Mới')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Thêm Chi Tiêu Mới</h1>
            <a href="{{ route('expenses.index') }}" class="text-blue-600 hover:text-blue-800">
                <i class="fas fa-arrow-left mr-2"></i>Quay lại
            </a>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('expenses.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label for="amount" class="block text-sm font-medium text-gray-700">Số tiền</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <input type="number" name="amount" id="amount" required step="1000"
                        class="block w-full pr-12 border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                        value="{{ old('amount') }}"
                        placeholder="0">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 sm:text-sm">VND</span>
                    </div>
                </div>
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Mô tả</label>
                <input type="text" name="description" id="description" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    value="{{ old('description') }}"
                    placeholder="Nhập mô tả chi tiêu">
            </div>

            <div>
                <label for="date" class="block text-sm font-medium text-gray-700">Ngày chi tiêu</label>
                <input type="date" name="date" id="date" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    value="{{ old('date', date('Y-m-d')) }}">
            </div>

            <div>
                <label for="category_id" class="block text-sm font-medium text-gray-700">Danh mục</label>
                <select name="category_id" id="category_id" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Chọn danh mục</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex justify-end">
                <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
                    <i class="fas fa-save mr-2"></i>Lưu Chi Tiêu
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 