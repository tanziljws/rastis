<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Foto>
 */
class FotoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'galery_id' => null, // Will be set in seeder
            'file' => 'foto_' . fake()->numberBetween(1, 1000) . '.jpg',
            'judul' => fake()->sentence(),
        ];
    }
}
