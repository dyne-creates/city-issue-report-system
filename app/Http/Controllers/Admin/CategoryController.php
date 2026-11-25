<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Category;
use App\Models\Department;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = DB::table('categories')
            ->join('departments', 'categories.department_id', '=', 'departments.id')
            ->select(
                'categories.id',
                'categories.department_id',
                'categories.name',
                'categories.description',
                'departments.name AS department_name'
            );
        // search filter for category and department (input)
        if ($request->filled('search')) {
            $query->where(function ($query) use ($request) {
                $query->where('categories.name', 'like', '%' . $request->search . '%')
                    ->orWhere('categories.description', 'like', '%' . $request->search . '%')
                    ->orWhere('departments.name', 'like', '%' . $request->search . '%');
            });
        }
        // search by department
        if ($request->filled('department_id')) {
            $query->where('categories.department_id', $request->department_id);
        }

        $resultCount = (clone $query)->count();

        $categories = $query->orderBy('departments.name')->orderBy('categories.name')->paginate(10)->withQueryString();

        $departments = DB::table('departments')->orderBy('name')->get();

        return view('admin.categories.index', compact('categories', 'departments', 'resultCount'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::all()->sortBy('name'); // sort the department by name (A-Z)

        return view('admin.categories.create', compact('departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        Category::create($request->validated());

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        $departments = Department::all();

        return view('admin.categories.edit', compact('category', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update($request->validated());

        return redirect()->route('admin.categories.index', $request->only('page', 'search'))
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category, Request $request)
    {
        try {
            $category->delete();

            $page = max((int) $request->page, 1);

            $query = Category::query();

            if ($request->filled('search')) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }

            $total = $query->count();

            $lastPage = max((int) ceil($total / 10), 1);

            if ($page > $lastPage) {
                $page = $lastPage;
            }

            return redirect()->route('admin.categories.index', [
                'page' => $page,
                'search' => $request->search,
            ])->with('success', 'Category deleted successfully.');
        } catch (QueryException) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Category cannot be deleted because it is still being used.');
        }
    }
}
