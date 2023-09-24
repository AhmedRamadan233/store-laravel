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
        $products = Product::filter($filters)->paginate();

        return response()->json(['data' => $products]);
    }
}
