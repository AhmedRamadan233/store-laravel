<?php

namespace App\Models;

use App\Observers\CartObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cookie;

class Cart extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $fillable=[
        'cookie_id',
        'user_id',
        'product_id',
        'quantity',
        'options'
    ];

    // opservars (events)
        // events occure on model (edit delete ....)
            // creating , created  updating , updated 
            // saving , saved  deleteing , deleted  restoring , restored retrieved
    // بقوله و انتا بتجهز عملية الانسيرت خد ال الي دي دا معاك
    protected static function booted(){
        static::observe(CartObserver::class);
        // static::creating(function(Cart $cart){
        //     $cart->id = Str::uuid();
        // });
        static::addGlobalScope('cookie_id' ,function(Builder $builder){
            $builder->where('cookie_id' , '=' , Cart::getCookieId());
        });
    } 
    public static function getCookieId(){
        $cookie_id = Cookie::get('cart_id');
        if(!$cookie_id){
            $cookie_id = Str::uuid();
            Cookie::queue('cart_id', $cookie_id , 30*24*60);
        }

        return $cookie_id;
    }
    public function user(){
        return $this->belongsTo(User::class)->withDefault([
            'name' => 'Anonymous'
        ]);
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }
}
