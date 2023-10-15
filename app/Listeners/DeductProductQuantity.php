<?php

namespace App\Listeners;

use App\Facades\Cart;
use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class DeductProductQuantity
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle($order, $user = null): void
    {
        // Update products' quantity
        foreach ($order->products as $product) {
            $quantityToDecrement = $product->order_items->quantity; 
            $product->decrement('quantity', $quantityToDecrement);
        }
    }
    
}
