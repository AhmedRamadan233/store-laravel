<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public $notification;
    public $newCount;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $success = "now success dashboard";
        return response()->json(['message' => $success , 'Data'=>$user, 'status' => 200]);
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function notification()
    {
        $user = Auth::user();
        $notifications = $user->notifications()->take(10)->get();
        $newCount = $user->unreadNotifications->count();

        return response()->json(['notifications' => $notifications, 'newCount' => $newCount]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function markNotificationAsRead($notificationId)
    {
        $user = Auth::user();
    
        // Find the notification by its ID and mark it as read
        $notification = $user->notifications()->find($notificationId);
        if ($notification) {
            $notification->markAsRead();
        }
    
        // Get the updated list of notifications and unread count
        $notifications = $user->notifications()->get();
        $newCount = $user->unreadNotifications->count();
    
        return response()->json(['notifications' => $notifications, 'newCount' => $newCount]);
    }

    /**
     * Display the specified resource.
     */
    public function someAction()
    {
        // Replace 1 with the actual ID of the notification you want to mark as read.
        $updatedNotifications = $this->markNotificationAsRead(1);
        // Use $updatedNotifications as needed.
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
