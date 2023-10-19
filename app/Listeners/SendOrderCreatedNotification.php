<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Models\User;
use App\Notifications\OrderCreatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;


class SendOrderCreatedNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }
    
    // public function handle(OrderCreated $event): void
    // {

    //     // For Multiple Users
    //     $admins = User::where('store_id', $event->order->store_id)->get();
    //     // dd($admins);
    //     Notification::send($admins, new OrderCreatedNotification($event->order));
    // }


    /**
     * Handle the event.
     */
    public function handle(OrderCreated $event)
    {
       
        $order=$event->order;
        // dd($order);
        $user = User::where('store_id', $order->store_id)->first();
        // dd($user);

        $user->notify(new OrderCreatedNotification($order));        
        // // dd($user);



        // return response()->json([
        //     'user'=>$user,
        // ]);
        // Mail::to($user->email)->send(new OrderCreatedNotification($order));
        // Notification::send($user,new OrderCreatedNotification($order));

        
        // $users = User::where('store_id' , $order->store_id)->get();
        // // return response()->json([
        // //     'users'=>  $users
        // //   ]);
        // // dd($users);
        // Notification::send($users ,new OrderCreatedNotification($order));

    }




}
