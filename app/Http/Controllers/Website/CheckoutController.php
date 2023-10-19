<?php

namespace App\Http\Controllers\website;

use App\Events\OrderCreated;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Notifications\OrderCreatedNotification;
use App\Repositories\Cart\CartRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class CheckoutController extends Controller
{
    public function create(CartRepository $cart)
    {
        if($cart->get()->count() == 0){
            return response()->json(['route' => 'home']);

        }
        $items = $cart->get();
        $items = $items->groupBy('product.store_id');
        return response()->json(['data' => $items]);
    }

    
    public function store(Request $request, CartRepository $cart)
    {
        // $request->validate([
        //     'addr.billing.first_name' => ['required', 'string', 'max:255'],
        //     'addr.billing.last_name' => ['required', 'string', 'max:255'],
        //     'addr.billing.email' => ['required', 'email', 'max:255'],
        //     'addr.billing.phone_number' => ['required', 'string', 'max:255'],
        //     'addr.billing.city' => ['required', 'string', 'max:255'],
        // ]);

        $items = $cart->get();
        $items = $items->groupBy('product.store_id');
        $items = $items->all();
        DB::beginTransaction();
    try {
        foreach ($items as $store_id => $cart_items) {
            // Create a new order
            $order = Order::create([
                'store_id' => $store_id, // Set your store ID here if applicable
                'user_id' => Auth::id(),
                'payment_method' => 'cod', // You can customize the payment method
            ]);

            foreach ($cart_items as $item) {
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
            // event('order.created' , $order , Auth::user());
            event(new OrderCreated($order));

        } 
    } catch (\Throwable $e) {
        DB::rollBack();
        // throw $e;
    }

    return response()->json(['message' => 'Order created successfully' ]);
    }
}