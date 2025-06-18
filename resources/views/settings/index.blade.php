@extends('layouts.app')

@section('title', 'Cài đặt')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Cài đặt</h1>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <!-- Tabs -->
        <div class="flex border-b">
            <button id="tab-profile" class="px-6 py-3 font-medium text-blue-600 border-b-2 border-blue-500 flex-1">Thông tin cá nhân</button>
            <button id="tab-password" class="px-6 py-3 font-medium text-gray-600 hover:text-blue-600 flex-1">Đổi mật khẩu</button>
            <button id="tab-delete" class="px-6 py-3 font-medium text-red-600 hover:text-red-700 flex-1">Xóa tài khoản</button>
        </div>

        <!-- Tab Contents -->
        <div class="p-6">
            <!-- Thông tin cá nhân -->
            <div id="content-profile" class="tab-content">
                <form action="{{ route('settings.update-profile') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Họ tên</label>
                        <input type="text" name="name" id="name" value="{{ old('name', Auth::user()->name) }}" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', Auth::user()->email) }}" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Cập nhật thông tin
                        </button>
                    </div>
                </form>
            </div>

            <!-- Đổi mật khẩu -->
            <div id="content-password" class="tab-content hidden">
                <form action="{{ route('settings.update-password') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Mật khẩu hiện tại</label>
                        <input type="password" name="current_password" id="current_password" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        @error('current_password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Mật khẩu mới</label>
                        <input type="password" name="password" id="password" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Xác nhận mật khẩu mới</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Cập nhật mật khẩu
                        </button>
                    </div>
                </form>
            </div>

            <!-- Xóa tài khoản -->
            <div id="content-delete" class="tab-content hidden">
                <div class="border-l-4 border-red-500 bg-red-50 p-4 mb-4">
                    <p class="text-gray-700">Khi bạn xóa tài khoản, tất cả dữ liệu của bạn sẽ bị xóa vĩnh viễn. Hãy chắc chắn rằng bạn đã sao lưu dữ liệu quan trọng trước khi thực hiện.</p>
                </div>
                
                <form action="{{ route('settings.delete-account') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Nhập mật khẩu để xác nhận</label>
                        <input type="password" name="password" id="delete_password" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="flex items-center">
                            <input type="checkbox" name="delete_data" value="1" class="rounded border-gray-300 text-red-600 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700">Xóa toàn bộ dữ liệu (chi tiêu, thu nhập, danh mục)</span>
                        </label>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="return confirm('Bạn có chắc chắn muốn xóa tài khoản? Hành động này không thể hoàn tác.')">
                            Xóa tài khoản
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Xử lý chuyển tab
    document.addEventListener('DOMContentLoaded', function() {
        const tabs = document.querySelectorAll('[id^="tab-"]');
        const contents = document.querySelectorAll('[id^="content-"]');
        
        // Xử lý khi click vào tab
        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                // Xóa class active khỏi tất cả tabs
                tabs.forEach(t => {
                    t.classList.remove('text-blue-600', 'border-b-2', 'border-blue-500');
                    t.classList.add('text-gray-600', 'hover:text-blue-600');
                });
                
                // Thêm class active cho tab được click
                this.classList.add('text-blue-600', 'border-b-2', 'border-blue-500');
                this.classList.remove('text-gray-600', 'hover:text-blue-600');
                
                // Ẩn tất cả contents
                contents.forEach(content => content.classList.add('hidden'));
                
                // Hiển thị content tương ứng
                const tabId = this.id;
                const contentId = tabId.replace('tab-', 'content-');
                document.getElementById(contentId).classList.remove('hidden');
            });
        });
        
        // Mặc định hiển thị tab đầu tiên
        tabs[0].click();
        
        // Xử lý khi có hash trong URL
        if (window.location.hash) {
            const hash = window.location.hash.substring(1); // Bỏ dấu # ở đầu
            const tabId = 'tab-' + hash;
            const tab = document.getElementById(tabId);
            if (tab) tab.click();
        }
    });
</script>
@endpush
@endsection