<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function products_can_be_listed()
    {
        $product = Product::factory()->create();

        $response = $this->get('/api/products');

        $response->assertOk();
        $response->assertJsonFragment(['name' => $product->name]);
    }

    /** @test */
    public function a_product_can_be_created()
    {
        $productData = [
            'name' => 'Test Product',
            'price' => 100.00,
            'description' => 'Test Description',
        ];

        $response = $this->postJson('/api/products', $productData);

        $response->assertCreated();
        $this->assertDatabaseHas('products', ['name' => 'Test Product']);
    }
}
