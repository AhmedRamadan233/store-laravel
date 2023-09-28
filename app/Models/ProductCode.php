<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'product_code',
    ];
    public function product()
    {
        return $this->hasOne(Product::class, 'product_code_id', 'id');
    }
}
