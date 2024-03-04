<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SaleController extends Controller
{
    public function show($id)
    {
        try {
            $sale = Sale::with('products')->findOrFail($id);

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
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Venda não encontrada'], 404);
        }
    }

    public function index()
    {
        $sales = Sale::with('products')->get();
        return response()->json($sales);
    }

    public function cancelSale($id)
    {
        $sale = Sale::findOrFail($id);
        $sale->status = 'cancelled';
        $sale->save();

        return response()->json(['message' => 'Venda cancelada com sucesso'], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric',
            'status' => 'required|string|max:255',
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.amount' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric',
        ]);

        DB::beginTransaction();

        try {
            $sale = Sale::create([
                'amount' => $validated['amount'],
                'status' => $validated['status'],
            ]);

            foreach ($validated['products'] as $product) {
                $sale->products()->attach($product['id'], [
                    'amount' => $product['amount'],
                    'price' => $product['price'],
                ]);
            }

            DB::commit();
            return response()->json($sale->load('products'), 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Falha ao criar venda', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.amount' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric',
        ]);

        DB::beginTransaction();

        try {
            $sale = Sale::findOrFail($id);

            // Limpa os produtos atuais antes de adicionar os novos
            $sale->products()->detach();

            foreach ($validated['products'] as $product) {
                $sale->products()->attach($product['id'], [
                    'amount' => $product['amount'],
                    'price' => $product['price'],
                ]);
            }

            DB::commit();
            return response()->json($sale->load('products'), 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Falha ao atualizar venda', 'error' => $e->getMessage()], 500);
        }
    }
}
