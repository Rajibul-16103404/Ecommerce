<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Services\SearchService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    private SearchService $searchService;

    public function __construct(SearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    public function index()
    {
        $categories = Category::withCount('products')->get();

        return view('categories.index', compact('categories'));
    }

    public function show(Category $category)
    {
        $products = $this->searchService->getProductsByCategory($category->id);

        return view('categories.show', compact('category', 'products'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
