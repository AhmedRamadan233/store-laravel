<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('login');
    }
    // protected function redirectTo(Request $request): ?string
    // {
    //     if ($request->expectsJson()) {
    //         // For JSON requests, return null to indicate no redirection
    //         return null;
    //     }
    
    //     // Check the prefix of the request URL
    //     if ($request->is('auth/dashboard/*')) {
    //         // If the URL matches the 'auth/dashboard' prefix, redirect to the 'dashboard.login' route
    //         return route('dashboard.login');
    //     } elseif ($request->is('auth/website/*')) {
    //         // If the URL matches the 'auth/website' prefix, redirect to the 'website.login' route
    //         return route('website.login');
    //     }
    
    //     // For other cases, you can return a default route or null if you want no redirection
    //     return route('login'); // Default login route
    // }
}