<?php

namespace Database\Factories;

use App\Models\Sale;
use Illuminate\Database\Eloquent\Factories\Factory;

class SaleFactory extends Factory
{
    protected $model = Sale::class;

    public function definition()
    {
        return [
            'amount' => $this->faker->randomFloat(2, 100, 5000), // Gera um valor entre 100 e 5000
            'status' => $this->faker->randomElement(['pending', 'completed', 'cancelled']), // Escolhe um status aleatório
        ];
    }
}
