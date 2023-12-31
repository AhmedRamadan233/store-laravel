<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Dashboard\CategoryController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\ProductController;
use App\Http\Controllers\Dashboard\ProfileController;
use App\Http\Controllers\Dashboard\ProductCodeController;
use App\Http\Controllers\Website\HomeController;

use App\Http\Controllers\Website\ProductController as WebsiteProductController;

use App\Http\Controllers\Website\CartController;
use App\Http\Controllers\website\CheckoutController;
use Laravel\Sanctum\Sanctum;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});
 // Authentication and registration routes
 Route::prefix('auth/dashboard')->middleware('guest')->group(function () {
    // User registration route
    Route::post('/register', [RegisteredUserController::class, 'store'])->name('api.register');
    // User login route     
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('api.login');
});


Route::prefix('auth/dashboard')->middleware('auth:sanctum')->group(function () {
    // Forgot password route
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('api.password.email');
    // Reset password route
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('api.password.store');
    // Verify email route
    Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])->middleware(['auth', 'signed', 'throttle:6,1'])->name('api.verification.verify');
    // Resend email verification notification route
    Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])->middleware(['auth', 'throttle:6,1'])->name('api.verification.send');
    // User logout route
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('api.logout');
    
});

// ->middleware(['auth:sanctum', 'auth.type:admin,super-admin'])->

Route::prefix('dashboard')->middleware(['auth:sanctum', 'auth.type:admin,super-admin'])->group(function () {
    // Dashboard index route
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/notification', [DashboardController::class, 'notification']);
    Route::get('/notification/{notification}', [DashboardController::class, 'markNotificationAsRead']);
    Route::get('/mark-notification-read/{notificationId}', [DashboardController::class, 'markNotificationAsRead']);


    // Categories routes
    Route::prefix('categories')->group(function () {
        // Get all Categories
        Route::get('/', [CategoryController::class, 'getAllCategories'])->name('getAllCategories');
        // Get Category by id
        Route::get('/{category}', [CategoryController::class, 'getCategoryById'])->name('getCategoryById')->where('category','\d+');
        // Create a Category
        Route::post('/', [CategoryController::class, 'createCategory'])->name('createCategory');
        // Update a Category
        Route::put('/{id}', [CategoryController::class, 'updateCategory'])->name('updateCategory');
        // Delete a Category
        Route::delete('/{id}', [CategoryController::class, 'deleteCategory'])->name('deleteCategory');
        // Get trashed Categories
        Route::get('/trashed', [CategoryController::class, 'getCategoriesTrashing'])->name('getCategoriesTrashing');
        // Restore trashed Categories
        Route::put('/{id}/restore', [CategoryController::class, 'getCategoriesRestoring'])->name('getCategoriesRestoring');
        // Delete trashed categories
        Route::delete('/{id}/forcedelete', [CategoryController::class, 'deleteCategoriesForced'])->name('deleteCategoriesForced');

    });
    // products routes
    Route::prefix('products')->group(function () {
        // Get all Product
        Route::get('/', [ProductController::class, 'getAllProducts'])->name('getAllProducts');
        Route::put('/{id}', [ProductController::class, 'updateProduct'])->name('getAllProducts');
        Route::get('/{product}', [ProductController::class, 'getProductById'])->name('getProductById')->where('product','\d+');
        Route::post('/', [ProductController::class, 'createProduct'])->name('createProduct');      
    });
    // profiles routes
    Route::prefix('profiles')->group(function () {
        // Get My profile
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
    });
    // profiles routes
    Route::prefix('productcodes')->group(function () {
        // Get My profile
        Route::get('/', [ProductCodeController::class, 'getAllProductCodes'])->name('getAllProductCodes');
        // Route::patch('/', [ProductCodeController::class, 'update'])->name('update');
    });


});

Route::prefix('website')->middleware(['auth:sanctum'])->group(function(){
    // website routes
    Route::get('/', [HomeController::class, 'index'])->name('website');

    // products routes
    Route::prefix('products')->group(function () {
        // Get all Product
        Route::get('/', [WebsiteProductController::class, 'getAllProducts'])->name('getAllProducts');
        Route::get('/{product:slug}', [WebsiteProductController::class, 'getProductBySlug'])->name('getProductBySlug');
    });
    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('cart.index');
        Route::post('/store', [CartController::class, 'store'])->name('cart.store');
        Route::put('/update', [CartController::class, 'update'])->name('cart.update');
        Route::delete('/destroy/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
        Route::get('/total', [CartController::class, 'total'])->name('cart.total');

    });
    Route::get('/checkout', [CheckoutController::class, 'create'])->name('checkout.create');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');


});
