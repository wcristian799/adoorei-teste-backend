<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale; // Chamar o model

class SaleController extends Controller
{
    
    public function show($id)
{
    $sale = Sale::with('products')->find($id);

    if (!$sale) {
        return response()->json(['message' => 'Sale not found'], 404);
    }

    $formattedSale = [
        'sales_id' => $sale->id,
        'amount' => $sale->amount,
        'products' => $sale->products->map(function ($product) {
            return [
                'product_id' => $product->id,
                'nome' => $product->name,
                'price' => $product->pivot->price,
                'amount' => $product->pivot->amount,
            ];
        })->toArray(),
    ];

    return response()->json($formattedSale);
}

}
