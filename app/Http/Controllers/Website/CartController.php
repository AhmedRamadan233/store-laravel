<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Repositories\Cart\CartRepository;
use Illuminate\Http\Request;
use App\Models\Product;
class CartController extends Controller
{


    public function index(CartRepository $cart)
    {
        // dd( $cart);

        $items =  $cart->get();
        $total = $cart->total();
        // return view('product');

        return response()->json(['data' => $items , 'total' => $total]);
    }

    public function store(Request $request, CartRepository $cart)
    {
        $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);
    
        $product = Product::findOrFail($request->post('product_id'));
        // dd($product);    
        $cart->add($product, $request->input('quantity'));
        
        return response()->json(['message' => 'Product added to cart successfully' , ]);
    }
    

    public function update(Request $request, CartRepository $cart)
    {
        $this->validate($request, [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');

        // Get the product by its ID
        $product = Product::findOrFail($productId);

        // Now, you can use $product->id to access the ID if needed

        // Update the cart with the product and quantity
        $cart->update($product, $quantity);

        return response()->json(['message' => 'Product updaded to cart successfully']);
    }

    public function destroy(CartRepository $cart , $id)
    {
        $cart->delete($id);
        return response()->json(['message' => 'Product removed from cart successfully']);
    }

    public function total(CartRepository $cart)
    {
        $total = $cart->total();
        return response()->json(['total' => $total]);
    }
}
