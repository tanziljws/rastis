<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call seeders in the correct order to maintain foreign key relationships
        $this->call([
            KategoriSeeder::class,
            PetugasSeeder::class,
            PostSeeder::class,
            GalerySeeder::class,
            FotoSeeder::class,
            ProfileSeeder::class,
        ]);

        // Keep the default User factory for testing
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
