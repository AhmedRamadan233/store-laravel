<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Models\User;
use App\Notifications\OrderCreatedNotification;
use Exception;
use Illuminate\Support\Facades\Notification;


class SendOrderCreatedNotification
{
    public function __construct()
    {
    }

    // public function handle(OrderCreated $event): void
    // {
        
    
    //     $admins = User::where('store_id', $event->order->store_id)->get();
    //     Notification::send($admins, new OrderCreatedNotification($event->order));
      
    // }

    // public function handle(OrderCreated $event): void
    // {
    //     // Log a message when the listener is called
    //     Log::info('OrderCreated event listener called.');
    
    //     // Log the order ID to check if the event is receiving the order correctly
    //     Log::info('Order ID: ' . $event->order->id);
    
    //     // Query to get admins
    //     $admins = User::where('store_id', $event->order->store_id)->get();
        
    //     // Log the number of admins found
    //     Log::info('Number of admins found: '. $event->order->id);
    //     // Send the notification
    //     Notification::sendNow($admins, new OrderCreatedNotification($event->order));
    
    //     // Log a message after sending the notification
    //     Log::info('OrderCreatedNotification sent to admins.');
    
    //     // You can also log more information about the event or the order if needed
    //     Log::info('Event data: ' . print_r($event, true));
    //     Log::info('Order data: ' . print_r($event->order, true));
    // }
    /**
     * Handle the event.
     */
    // public function handle(OrderCreated $event)
    // {
       
    //     $order=$event->order;
    //     $user = User::where('store_id', $order->store_id)->first();
    //     Notification::send($user,new OrderCreatedNotification($order));

        
    //     // $users = User::where('store_id' , $order->store_id)->get();
    //     // // return response()->json([
    //     // //     'users'=>  $users
    //     // //   ]);
    //     // // dd($users);
    //     // Notification::send($users ,new OrderCreatedNotification($order));

    // }

    public function handle(OrderCreated $event)
    {
        $order = $event->order;
        $users = User::where('store_id', $order->store_id)->get();

        // Test before sending the notification
        // dd('OrderCreated event listener called.aaaaaaaaaaaaaaaaaaaaaa', $users);
        Notification::send($users, new OrderCreatedNotification($order));
        // Test after sending the notification
        // dd('OrderCreatedNotification bbbbbbbbbbbbbbb', $users);
   
    }


}
