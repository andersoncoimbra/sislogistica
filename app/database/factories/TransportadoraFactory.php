<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transportadora>
 */
class TransportadoraFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nome' => $this->faker->company,
            'endereco' => $this->faker->streetAddress,
            'bairro' => $this->faker->citySuffix,
            'cidade' => $this->faker->city,
            'UF' => $this->faker->stateAbbr,
            'limite_credito' => $this->faker->randomFloat(2, 1000, 10000),
            'data_analise_credito' => $this->faker->date(),
        ];
    }
}
