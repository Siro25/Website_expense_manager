<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ExpenseController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the expenses.
     */
    public function index(Request $request)
    {
        $query = Auth::user()->expenses()->with('category');

        // Filter by date range
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('date', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay()
            ]);
        }

        // Filter by category
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Sort by date
        $query->orderBy('date', 'desc');

        $expenses = $query->paginate(10);
        $categories = Auth::user()->categories;

        if ($request->wantsJson()) {
            return response()->json($expenses);
        }

        return view('expenses.index', compact('expenses', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created expense.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string|max:255',
            'date' => 'required|date',
            'category_id' => 'required|exists:categories,id',
        ]);

        $expense = Auth::user()->expenses()->create($validated);

        return response()->json([
            'message' => 'Chi tiêu đã được thêm thành công',
            'expense' => $expense->load('category')
        ], 201);
    }

    /**
     * Display the specified expense.
     */
    public function show(Expense $expense)
    {
        $this->authorize('view', $expense);
        return response()->json($expense->load('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified expense.
     */
    public function update(Request $request, Expense $expense)
    {
        $this->authorize('update', $expense);

        $validated = $request->validate([
            'amount' => 'sometimes|required|numeric|min:0',
            'description' => 'sometimes|required|string|max:255',
            'date' => 'sometimes|required|date',
            'category_id' => 'sometimes|required|exists:categories,id',
        ]);

        $expense->update($validated);

        return response()->json([
            'message' => 'Chi tiêu đã được cập nhật thành công',
            'expense' => $expense->load('category')
        ]);
    }

    /**
     * Remove the specified expense.
     */
    public function destroy(Expense $expense)
    {
        $this->authorize('delete', $expense);
        
        $expense->delete();

        return response()->json([
            'message' => 'Chi tiêu đã được xóa thành công'
        ]);
    }

    /**
     * Get statistics for expenses.
     */
    public function statistics(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth());

        // Total expenses
        $total = Auth::user()->expenses()
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('amount');

        // Expenses by category
        $byCategory = Auth::user()->expenses()
            ->whereBetween('date', [$startDate, $endDate])
            ->select('category_id', DB::raw('SUM(amount) as total'))
            ->groupBy('category_id')
            ->with('category')
            ->get();

        // Daily expenses
        $daily = Auth::user()->expenses()
            ->whereBetween('date', [$startDate, $endDate])
            ->select(DB::raw('DATE(date) as date'), DB::raw('SUM(amount) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json([
            'total' => $total,
            'by_category' => $byCategory,
            'daily' => $daily
        ]);
    }
}
