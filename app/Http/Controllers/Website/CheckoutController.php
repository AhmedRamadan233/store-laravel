<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Repositories\Cart\CartRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function create(CartRepository $cart)
    {
        $items = $cart->get();
        $items = $items->groupBy('product.store_id');
        return response()->json(['data' => $items]);
    }

    
    public function store(Request $request, CartRepository $cart)
    {
        $request->validate([
            // Add your validation rules here as needed
        ]);

        DB::beginTransaction();

        try{

            // Create a new order
            $order = Order::create([
                'store_id' => 1, // Set your store ID here if applicable
                'user_id' => Auth::id(),
                'payment_method' => 'cod', // You can customize the payment method
            ]);
            foreach ($cart->get() as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'price' => $item->product->price, // Corrected the price reference
                    'quantity' => $item->quantity,
                ]);
            }
            foreach ($request->post('addr') as $type => $address) {
                $address['type'] = $type;
                $order->addresses()->create($address);
            }
            DB::commit();
            return response()->json(['message' => 'Order created successfully']);
        }
        catch(\Throwable $e){
            DB::rollBack();
        }
    }
}