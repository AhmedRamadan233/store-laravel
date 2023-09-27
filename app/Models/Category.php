<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory , SoftDeletes;
    protected $fillable = ['name', 'slug', 'parent_id', 'description', 'status'];


    public function products(){
        return $this->hasMany(Product::class , 'category_id' , 'id');
    }
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id' , 'id')
            ->withDefault([ 'name'=> '-']);
    }

    public function children()
    {
        return $this->belongsTo(Category::class, 'parent_id' , 'id')->withDefault('Main Category');
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
