<?php

namespace App\Http\Controllers;

use App\Models\IncomeCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class IncomeCategoryController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the income categories.
     */
    public function index()
    {
        $categories = Auth::user()->incomeCategories()->paginate(10);
        return view('income-categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created income category.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = Auth::user()->incomeCategories()->create($validated);

        return response()->json([
            'message' => 'Danh mục thu nhập đã được thêm thành công',
            'category' => $category
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(IncomeCategory $incomeCategory)
    {
        $this->authorize('view', $incomeCategory);
        return response()->json($incomeCategory);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified income category.
     */
    public function update(Request $request, IncomeCategory $incomeCategory)
    {
        $this->authorize('update', $incomeCategory);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $incomeCategory->update($validated);

        return response()->json([
            'message' => 'Danh mục thu nhập đã được cập nhật thành công',
            'category' => $incomeCategory
        ]);
    }

    /**
     * Remove the specified income category.
     */
    public function destroy(IncomeCategory $incomeCategory)
    {
        $this->authorize('delete', $incomeCategory);
        
        if ($incomeCategory->incomes()->exists()) {
            return response()->json([
                'message' => 'Không thể xóa danh mục này vì đã có thu nhập được gắn với nó'
            ], 422);
        }
        
        $incomeCategory->delete();

        return response()->json([
            'message' => 'Danh mục thu nhập đã được xóa thành công'
        ]);
    }
}
