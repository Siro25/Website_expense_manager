<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Income;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth());

        // Chuyển đổi sang Carbon nếu là string
        if (is_string($startDate)) {
            $startDate = Carbon::parse($startDate)->startOfDay();
        }
        if (is_string($endDate)) {
            $endDate = Carbon::parse($endDate)->endOfDay();
        }

        // Lấy dữ liệu chi tiêu theo danh mục
        $expensesByCategory = Expense::where('user_id', Auth::id())
            ->whereBetween('date', [$startDate, $endDate])
            ->select('category_id', DB::raw('SUM(amount) as total'))
            ->with('category:id,name')
            ->groupBy('category_id')
            ->get()
            ->map(function ($expense) {
                return [
                    'name' => $expense->category->name,
                    'total' => $expense->total,
                ];
            });
            
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

        // Lấy dữ liệu thu nhập theo danh mục
        $incomesByCategory = Income::where('user_id', Auth::id())
            ->whereBetween('date', [$startDate, $endDate])
            ->select('income_category_id', DB::raw('SUM(amount) as total'))
            ->with('category:id,name')
            ->groupBy('income_category_id')
            ->get()
            ->map(function ($income) {
                return [
                    'name' => $income->category->name,
                    'total' => $income->total,
                ];
            });

        // Lấy dữ liệu chi tiêu theo tháng
        $expensesByMonth = Expense::where('user_id', Auth::id())
            ->whereBetween('date', [$startDate->copy()->startOfYear(), $endDate])
            ->select(DB::raw('MONTH(date) as month'), DB::raw('SUM(amount) as total'))
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->mapWithKeys(function ($expense) {
                return [Carbon::create(null, $expense->month)->format('M') => $expense->total];
            });

        // Lấy dữ liệu thu nhập theo tháng
        $incomesByMonth = Income::where('user_id', Auth::id())
            ->whereBetween('date', [$startDate->copy()->startOfYear(), $endDate])
            ->select(DB::raw('MONTH(date) as month'), DB::raw('SUM(amount) as total'))
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->mapWithKeys(function ($income) {
                return [Carbon::create(null, $income->month)->format('M') => $income->total];
            });

        // Tổng thu nhập và chi tiêu
        $totalExpenses = $expensesByCategory->sum('total');
        $totalIncomes = $incomesByCategory->sum('total');
        $balance = $totalIncomes - $totalExpenses;

        return view('statistics.index', compact(
            'expensesByCategory',
            'incomesByCategory',
            'expensesByMonth',
            'incomesByMonth',
            'totalExpenses',
            'totalIncomes',
            'balance',
            'startDate',
            'endDate',
            'topExpenseCategories',
            'recentExpenses',
            'recentIncomes'
        ));
    }
}
