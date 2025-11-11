<?php

namespace Database\Seeders;

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
            UserSeeder::class,
            KategoriSeeder::class,
            PetugasSeeder::class,
            PostSeeder::class,
            GalerySeeder::class,
            FotoSeeder::class,
            ProfileSeeder::class,
        ]);
    }
}
