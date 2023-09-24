<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function getAllProducts(Request $request)
    {
        $filters = $request->query();
    
        $products = Product::filter($filters)->paginate();
        // leftJoin('categories as parents', 'parents.id', '=', 'categories.parent_id')
        //     ->select(['categories.*', 'parents.name as parent_name'])
        //     ->filter($filters)
        //     ->paginate();

        // return view('product', compact('products'));
    
        return response()->json(['data' => $products]);
    }
}
