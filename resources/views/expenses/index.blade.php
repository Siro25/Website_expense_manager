@extends('layouts.app')

@section('title', 'Danh Sách Chi Tiêu')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Quản lý chi tiêu</h1>
        <button onclick="openCreateModal()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Thêm chi tiêu
        </button>
    </div>

    <!-- Filters -->
    <div class="bg-white p-4 rounded-lg shadow mb-6">
        <form id="filterForm" class="flex gap-4">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700">Từ ngày</label>
                <input type="date" name="start_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700">Đến ngày</label>
                <input type="date" name="end_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700">Danh mục</label>
                <select name="category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="">Tất cả</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Lọc
                </button>
            </div>
        </form>
    </div>

    <!-- Expenses List -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Danh mục</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mô tả</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Số tiền</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($expenses as $expense)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $expense->date->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $expense->category->name }}</td>
                        <td class="px-6 py-4">{{ $expense->description }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ number_format($expense->amount) }} đ</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button onclick="openEditModal({{ $expense->id }})" class="text-indigo-600 hover:text-indigo-900">Sửa</button>
                            <button onclick="deleteExpense({{ $expense->id }})" class="ml-3 text-red-600 hover:text-red-900">Xóa</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            Chưa có chi tiêu nào
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $expenses->links() }}
        </div>
    </div>
</div>

<!-- Create/Edit Modal -->
<div id="expenseModal" class="fixed z-10 inset-0 overflow-y-auto hidden">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="expenseForm" class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4" id="modalTitle">Thêm chi tiêu mới</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Số tiền</label>
                        <input type="number" name="amount" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Mô tả</label>
                        <input type="text" name="description" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Ngày</label>
                        <input type="date" name="date" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Danh mục</label>
                        <select name="category_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mt-5 sm:mt-6 flex justify-end space-x-3">
                    <button type="button" onclick="closeModal()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Hủy
                    </button>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Lưu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
let currentExpenseId = null;

function openCreateModal() {
    currentExpenseId = null;
    document.getElementById('modalTitle').textContent = 'Thêm chi tiêu mới';
    document.getElementById('expenseForm').reset();
    document.getElementById('expenseModal').classList.remove('hidden');
}

function openEditModal(id) {
    currentExpenseId = id;
    document.getElementById('modalTitle').textContent = 'Sửa chi tiêu';
    
    // Fetch expense data and populate form
    fetch(`/expenses/${id}`, {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Không thể tải dữ liệu chi tiêu');
        }
        return response.json();
    })
    .then(data => {
        const form = document.getElementById('expenseForm');
        form.amount.value = data.amount;
        form.description.value = data.description;
        form.date.value = data.date.split('T')[0]; // Lấy phần ngày từ datetime
        form.category_id.value = data.category_id;
    })
    .catch(error => {
        alert(error.message);
        closeModal();
    });
    
    document.getElementById('expenseModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('expenseModal').classList.add('hidden');
}

document.getElementById('expenseForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const method = currentExpenseId ? 'PUT' : 'POST';
    const url = currentExpenseId ? `/expenses/${currentExpenseId}` : '/expenses';
    
    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify(Object.fromEntries(formData))
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => {
                throw new Error(err.message || 'Có lỗi xảy ra khi lưu chi tiêu');
            });
        }
        return response.json();
    })
    .then(data => {
        alert(data.message);
        closeModal();
        window.location.reload();
    })
    .catch(error => {
        alert(error.message);
    });
});

function deleteExpense(id) {
    if (confirm('Bạn có chắc muốn xóa chi tiêu này?')) {
        fetch(`/expenses/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => {
                    throw new Error(err.message || 'Có lỗi xảy ra khi xóa chi tiêu');
                });
            }
            return response.json();
        })
        .then(data => {
            alert(data.message);
            window.location.reload();
        })
        .catch(error => {
            alert(error.message);
        });
    }
}

document.getElementById('filterForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const params = new URLSearchParams(formData);
    window.location.href = `/expenses?${params.toString()}`;
});
</script>
@endpush 