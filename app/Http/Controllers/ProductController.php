<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all(); 
        return response()->json($products);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
        ]);

        $product = Product::create($validatedData);

        return response()->json($product, Response::HTTP_CREATED); // Utiliza a constante HTTP_CREATED para o código de status 201
    }
    

}
