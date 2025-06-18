<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Income;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Lấy tháng từ request, mặc định là tháng hiện tại
        $month = $request->input('month', Carbon::now()->format('Y-m'));
        $date = Carbon::createFromFormat('Y-m', $month);
        
        // Lấy thời gian đầu tháng và cuối tháng được chọn
        $startDate = $date->copy()->startOfMonth();
        $endDate = $date->copy()->endOfMonth();

        // Lấy top 3 danh mục chi tiêu nhiều nhất trong tháng
        $topExpenseCategories = Expense::where('user_id', Auth::id())
            ->whereBetween('date', [$startDate, $endDate])
            ->select('category_id', DB::raw('SUM(amount) as total'))
            ->with('category:id,name')
            ->groupBy('category_id')
            ->orderBy('total', 'desc')
            ->limit(3)
            ->get()
            ->map(function ($expense) {
                return [
                    'name' => $expense->category->name,
                    'total' => $expense->total,
                ];
            });
            
        // Lấy danh sách các tháng có dữ liệu chi tiêu
        $availableMonths = Expense::where('user_id', Auth::id())
            ->select(DB::raw('DATE_FORMAT(date, "%Y-%m") as month'))
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->pluck('month')
            ->unique();
            
        // Nếu không có tháng nào, thêm tháng hiện tại
        if ($availableMonths->isEmpty()) {
            $availableMonths = collect([Carbon::now()->format('Y-m')]);
        }
            
        // Lấy các khoản chi tiêu gần đây
        $recentExpenses = Expense::where('user_id', Auth::id())
            ->with('category:id,name')
            ->orderBy('date', 'desc')
            ->limit(5)
            ->get();
            
        // Lấy các khoản thu nhập gần đây
        $recentIncomes = Income::where('user_id', Auth::id())
            ->with('category:id,name')
            ->orderBy('date', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard.index', compact(
            'topExpenseCategories',
            'recentExpenses',
            'recentIncomes',
            'availableMonths',
            'month'
        ));
    }
}