<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Register a new user.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Tạo danh mục mặc định cho người dùng mới
        $defaultCategories = [
            ['name' => 'Ăn uống'],
            ['name' => 'Di chuyển'],
            ['name' => 'Giải trí'],
            ['name' => 'Mua sắm'],
            ['name' => 'Hóa đơn'],
            ['name' => 'Sức khỏe'],
            ['name' => 'Giáo dục'],
            ['name' => 'Khác'],
        ];

        foreach ($defaultCategories as $category) {
            $user->categories()->create($category);
        }

        // Tạo danh mục thu nhập mặc định
        $defaultIncomeCategories = [
            ['name' => 'Lương'],
            ['name' => 'Đầu tư'],
            ['name' => 'Kinh doanh'],
            ['name' => 'Thưởng'],
            ['name' => 'Khác'],
        ];

        foreach ($defaultIncomeCategories as $category) {
            $user->incomeCategories()->create($category);
        }

        Auth::login($user);
        return redirect()->route('dashboard')->with('success', 'Đăng ký thành công');
    }

    /**
     * Login user and create token
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => ['Thông tin đăng nhập không chính xác'],
            ]);
        }

        return redirect()->intended(route('dashboard'))->with('success', 'Đăng nhập thành công');
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Đăng xuất thành công');
    }

    /**
     * Get authenticated user
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
