<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\User;

class SettingsController extends Controller
{
    /**
     * Hiển thị trang cài đặt tài khoản
     */
    public function index()
    {
        return view('settings.index');
    }

    /**
     * Cập nhật thông tin người dùng
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        $user->update($validated);

        return redirect()->route('settings.index')->with('success', 'Thông tin tài khoản đã được cập nhật thành công.');
    }

    /**
     * Cập nhật mật khẩu người dùng
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('settings.index')->with('success', 'Mật khẩu đã được cập nhật thành công.');
    }

    /**
     * Xóa tài khoản người dùng
     */
    public function deleteAccount(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
            'delete_data' => ['boolean'],
        ]);

        $user = Auth::user();
        
        // Xóa dữ liệu nếu được yêu cầu
        if ($request->delete_data) {
            // Xóa chi tiêu
            $user->expenses()->delete();
            // Xóa danh mục chi tiêu
            $user->categories()->delete();
            // Xóa thu nhập
            $user->incomes()->delete();
            // Xóa danh mục thu nhập
            $user->incomeCategories()->delete();
        }

        // Xóa tài khoản
        $user->delete();

        // Đăng xuất
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Tài khoản của bạn đã được xóa thành công.');
    }
}