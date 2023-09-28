<?php

namespace App\Http\Controllers;

use App\Models\ProductCode;
use Illuminate\Http\Request;

class ProductCodeController extends Controller
{
    public function getAllProductCodes()
    {
        
        $productCodes = ProductCode::paginate();
        return response()->json(['data' => $productCodes]);
    }
}
