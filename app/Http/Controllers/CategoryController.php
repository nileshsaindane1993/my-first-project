<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class CategoryController extends Controller
{
    public function index(): Response
    {
        $categories = Category::query()
            ->withCount('products')
            ->latest()
            ->paginate(10)
            ->through(fn (Category $category) => [
                'id' => $category->id,
                'name' => $category->name,
                'status' => $category->status,
                'products_count' => $category->products_count,
                'created_at_formatted' => $category->created_at?->format('M d, Y'),
            ]);

        return Inertia::render('categories/Index', [
            'categories' => $categories,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('categories/Create');
    }

    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        Category::create($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Category created successfully.')]);

        return redirect()->route('categories.index');
    }

    public function edit(Category $category): Response
    {
        return Inertia::render('categories/Edit', [
            'category' => [
                'id' => $category->id,
                'name' => $category->name,
                'status' => $category->status,
            ],
        ]);
    }

    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        $category->update($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Category updated successfully.')]);

        return redirect()->route('categories.index');
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->products()->exists()) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('Categories with products cannot be deleted.')]);

            return redirect()->route('categories.index');
        }

        $category->delete();

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Category deleted successfully.')]);

        return redirect()->route('categories.index');
    }
}
