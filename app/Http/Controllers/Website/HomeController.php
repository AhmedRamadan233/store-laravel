<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with('category')
            ->take(8)
            ->active()
            ->latest()
            ->get();
               
        return response()->json(['data' => $products ,'status' => 200]);
    }
}
