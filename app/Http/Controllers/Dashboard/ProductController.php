<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function getAllProducts(Request $request)
    {
        $filters = $request->query();
        $products = Product::with(['category', 'store'])->filter($filters)->paginate();

        return response()->json(['data' => $products]);
    }
    // public function getAllProducts(Request $request)
    // {
    //     $filters = $request->query();
        
    //     $products = Product::leftJoin('categories', 'products.category_id', '=', 'categories.id')
    //     ->leftJoin('stores', 'products.store_id', '=', 'stores.id')
    //     ->select('products.*', 'categories.name as category_name', 'stores.name as store_name')
    //         ->filter($filters)
    //         ->paginate();

    //     return response()->json(['data' => $products]);
    // }
}
