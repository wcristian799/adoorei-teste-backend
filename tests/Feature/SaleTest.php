<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Sale;
use App\Models\Product;

class SaleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */ 
    public function a_sale_can_be_created()
    {
        $product = Product::factory()->create();
        $saleData = [
            'amount' => 300,
            'status' => 'active',
            'products' => [
                ['id' => $product->id, 'amount' => 2, 'price' => $product->price]
            ]
        ];

        $response = $this->postJson('/api/sales', $saleData);

        $response->assertCreated();
        $this->assertDatabaseHas('sales', ['amount' => 300]);
    }

    /** @test */
    public function sales_can_be_listed()
    {
        $sale = Sale::factory()->create();

        $response = $this->get('/api/sales');

        $response->assertOk();
        $response->assertJsonFragment(['status' => $sale->status]);
    }
}
