<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = ['amount', 'status'];

    // A função products define a relação entre vendas e produtos.
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_sale')
                    ->withPivot(['price', 'amount']);
    }
}
 