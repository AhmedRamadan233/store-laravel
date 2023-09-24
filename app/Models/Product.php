<?php

namespace App\Models;

use App\Models\Scopes\StoreScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Support\Facades\Auth;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'category_id',
        'name',
        'slug',
        'description',
        'image',
        'product_code',
        'price',
        'compare_price',
        'options',
        'rating',
        'features',
        'status',
    ];

    // static function booted as a bootsrab in service container 
    // as a constructor 
    // worked first when use this odel 
    // protected static function booted()
    // {
    //     // in controller can i use withoutGlobalScope
    //     static::addGlobalScope('store', function (Builder $builder) {
    //         $user = Auth::user();
            
    //         if ($user->store_id) {
    //             $builder->where('store_id', '=', $user->store_id);
    //         }
    //     });
    // }
    protected static function booted()
    {
        // that is error    "message": "Global scope must be an instance of Closure or Scope.",
        // he said i want a object from class not class 
        // static::addGlobalScope('store', StoreScope::class);
        static::addGlobalScope('store' , new StoreScope());
    }
    public function scopeFilter(EloquentBuilder $builder, $filters)
    {
        $name = $filters['name'] ?? null;

        $status = $filters['status'] ?? null;

        if ($name) {
            $builder->where('name', 'LIKE', "%$name%");
        }
        if ($status) {
            $builder->where('status', '=', $status);
        }

    }
}
