@extends('layouts.app')

@section('title', 'Quản lý Danh mục Chi tiêu')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Quản lý Danh mục Chi tiêu</h1>
        <button onclick="openCreateModal()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Thêm danh mục
        </button>
    </div>

    <!-- Categories List -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên danh mục</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($categories as $category)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $category->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button onclick="openEditModal({{ $category->id }}, '{{ $category->name }}')" class="text-indigo-600 hover:text-indigo-900">Sửa</button>
                            <button onclick="deleteCategory({{ $category->id }})" class="ml-3 text-red-600 hover:text-red-900">Xóa</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="px-6 py-4 text-center text-gray-500">
                            Chưa có danh mục chi tiêu nào
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $categories->links() }}
        </div>
    </div>
</div>

<!-- Create/Edit Modal -->
<div id="categoryModal" class="fixed z-10 inset-0 overflow-y-auto hidden">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="categoryForm" class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4" id="modalTitle">Thêm danh mục chi tiêu mới</h3>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tên danh mục</label>
                    <input type="text" name="name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
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
let currentCategoryId = null;

function showError(message) {
    alert(message || 'Đã có lỗi xảy ra. Vui lòng thử lại.');
}

function openCreateModal() {
    currentCategoryId = null;
    document.getElementById('modalTitle').textContent = 'Thêm danh mục chi tiêu mới';
    document.getElementById('categoryForm').reset();
    document.getElementById('categoryModal').classList.remove('hidden');
}

function openEditModal(id, name) {
    currentCategoryId = id;
    document.getElementById('modalTitle').textContent = 'Sửa danh mục chi tiêu';
    const form = document.getElementById('categoryForm');
    form.name.value = name;
    document.getElementById('categoryModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('categoryModal').classList.add('hidden');
}

document.getElementById('categoryForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const method = currentCategoryId ? 'PUT' : 'POST';
    const url = currentCategoryId ? `/categories/${currentCategoryId}` : '/categories';
    
    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(Object.fromEntries(formData))
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(data => {
                throw new Error(data.message || 'Không thể lưu danh mục chi tiêu');
            });
        }
        return response.json();
    })
    .then(data => {
        closeModal();
        window.location.reload();
    })
    .catch(error => {
        showError(error.message);
    });
});

function deleteCategory(id) {
    if (confirm('Bạn có chắc muốn xóa danh mục chi tiêu này?')) {
        fetch(`/categories/${id}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.message || 'Không thể xóa danh mục chi tiêu');
                });
            }
            return response.json();
        })
        .then(data => {
            window.location.reload();
        })
        .catch(error => {
            showError(error.message);
        });
    }
}
</script>
@endpush 