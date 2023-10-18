<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
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
