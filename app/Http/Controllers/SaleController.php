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
            return response()->json(['message' => 'Venda nao encontrada'], 404);
        }
    }

    public function index()
{
    $sales = Sale::with('products')->get();
    return response()->json($sales);
}
public function cancelSale(Request $request, $id) {
    $sale = Sale::findOrFail($id);
    $sale->status = 'cancelled';
    $sale->save();  

    return response()->json(['message' => 'Venda cancelada com sucesso'], 200);
}
public function cancelSale($id)
{
    $sale = Sale::findOrFail($id);
    $sale->status = 'cancelled';
    $sale->save();

    return response()->json(['message' => 'Venda cancelada com sucesso.'], 200);
}
 
public function update(Request $request, $id) {
    // Validar os dados recebidos
    $validated = $request->validate([
        'products' => 'required|array',
        'products.*.id' => 'required|exists:products,id',
        'products.*.amount' => 'required|integer|min:1',
        'products.*.price' => 'required|numeric',
    ]);

    // Iniciar transação para garantir a integridade dos dados
    DB::beginTransaction();
    try {
        // Encontrar a venda pelo ID
        $sale = Sale::findOrFail($id);

        // Para cada produto recebido, anexar ou atualizar na relação muitos-para-muitos
        foreach ($validated['products'] as $product) {
            // Anexar produto à venda com detalhes adicionais
            // Verifica se o produto já existe na venda para evitar duplicatas
            $exists = $sale->products()->where('product_id', $product['id'])->exists();
            if (!$exists) {
                $sale->products()->attach($product['id'], [
                    'amount' => $product['amount'],
                    'price' => $product['price'],
                ]);
            } else {
                // Atualizar informações se o produto já estiver na venda
                $sale->products()->updateExistingPivot($product['id'], [
                    'amount' => $product['amount'],
                    'price' => $product['price'],
                ]);
            }
        }

        // Se tudo ocorreu bem, commit nas alterações
        DB::commit();

        // Retornar a venda atualizada com os produtos relacionados
        return response()->json($sale->load('products'), 200);
    } catch (\Exception $e) {
        // Se algo deu errado, rollback nas alterações
        DB::rollBack();

        // Retornar erro
        return response()->json(['message' => 'Falha ao atualizar venda', 'error' => $e->getMessage()], 500);
    }
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
} 
