<?php

namespace App\Http\Controllers;

use App\Models\Income;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class IncomeController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the incomes.
     */
    public function index(Request $request)
    {
        $query = Auth::user()->incomes()->with('category');

        // Filter by date range
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('date', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay()
            ]);
        }

        // Filter by category
        if ($request->has('income_category_id')) {
            $query->where('income_category_id', $request->income_category_id);
        }

        // Sort by date
        $query->orderBy('date', 'desc');

        $incomes = $query->paginate(10);
        $categories = Auth::user()->incomeCategories;

        if ($request->wantsJson()) {
            return response()->json($incomes);
        }

        return view('incomes.index', compact('incomes', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created income.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string|max:255',
            'date' => 'required|date',
            'income_category_id' => 'required|exists:income_categories,id',
        ]);

        $income = Auth::user()->incomes()->create($validated);

        return response()->json([
            'message' => 'Thu nhập đã được thêm thành công',
            'income' => $income->load('category')
        ], 201);
    }

    /**
     * Display the specified income.
     */
    public function show(Income $income)
    {
        $this->authorize('view', $income);
        return response()->json($income->load('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified income.
     */
    public function update(Request $request, Income $income)
    {
        $this->authorize('update', $income);

        $validated = $request->validate([
            'amount' => 'sometimes|required|numeric|min:0',
            'description' => 'sometimes|required|string|max:255',
            'date' => 'sometimes|required|date',
            'income_category_id' => 'sometimes|required|exists:income_categories,id',
        ]);

        $income->update($validated);

        return response()->json([
            'message' => 'Thu nhập đã được cập nhật thành công',
            'income' => $income->load('category')
        ]);
    }

    /**
     * Remove the specified income.
     */
    public function destroy(Income $income)
    {
        $this->authorize('delete', $income);
        
        $income->delete();

        return response()->json([
            'message' => 'Thu nhập đã được xóa thành công'
        ]);
    }
}
