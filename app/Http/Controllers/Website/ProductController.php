<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function getAllProducts(Request $request)
    {
        $filters = $request->query();
        $products = Product::with(['category', 'store' , 'tags' , 'productCode'])
            ->filter($filters)
            ->active()
            ->latest()
            ->paginate();

        return response()->json(['data' => $products]);
    }

    public function getProductBySlug(Product $product)
    {
        if ($product->status != 'active'){
            return response()->json(['data' => 'Not found Data' ]);

        }else{
            $productWithDetails = $product->load(['category', 'store', 'tags', 'productCode']);

        }

        return response()->json(['data' => $productWithDetails ]);
    }
}
