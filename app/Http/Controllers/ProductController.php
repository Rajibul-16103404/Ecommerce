<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\SearchService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private SearchService $searchService;

    public function __construct(SearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    public function home()
    {
        $featured = $this->searchService->getFeaturedProducts(8);
        $trending = $this->searchService->getTrendingProducts(8);

        return view('home', compact('featured', 'trending'));
    }

    public function index(Request $request)
    {
        $products = $this->searchService->search(
            query: $request->get('search'),
            categoryId: $request->get('category'),
            minPrice: $request->get('min_price'),
            maxPrice: $request->get('max_price'),
            sort: $request->get('sort', 'latest')
        );

        return view('products.index', compact('products'));
    }

    public function show(Product $product)
    {
        $product->increment('views');
        $related = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();

        return view('products.show', compact('product', 'related'));
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
