<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'judul' => fake()->sentence(),
            'kategori_id' => null, // Will be set in seeder
            'isi' => fake()->paragraphs(3, true),
            'petugas_id' => null, // Will be set in seeder
            'status' => fake()->randomElement(['published', 'draft', 'archived']),
        ];
    }
}
