<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CategoryController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the categories.
     */
    public function index()
    {
        $categories = Auth::user()->categories()->paginate(10);
        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created category.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = Auth::user()->categories()->create($validated);

        return response()->json([
            'message' => 'Danh mục đã được thêm thành công',
            'category' => $category
        ], 201);
    }

    /**
     * Display the specified category.
     */
    public function show(Category $category)
    {
        $this->authorize('view', $category);
        return response()->json($category);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified category.
     */
    public function update(Request $request, Category $category)
    {
        $this->authorize('update', $category);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category->update($validated);

        return response()->json([
            'message' => 'Danh mục đã được cập nhật thành công',
            'category' => $category
        ]);
    }

    /**
     * Remove the specified category.
     */
    public function destroy(Category $category)
    {
        $this->authorize('delete', $category);

        if ($category->expenses()->exists()) {
            return response()->json([
                'message' => 'Không thể xóa danh mục này vì đã có chi tiêu được gắn với nó'
            ], 422);
        }

        $category->delete();

        return response()->json([
            'message' => 'Danh mục đã được xóa thành công'
        ]);
    }

    /**
     * Get expenses summary for a category.
     */
    public function expenses(Category $category)
    {
        $this->authorize('view', $category);
        
        $expenses = $category->expenses()
            ->with('user')
            ->orderBy('date', 'desc')
            ->paginate(10);

        return response()->json([
            'category' => $category,
            'expenses' => $expenses
        ]);
    }
}
