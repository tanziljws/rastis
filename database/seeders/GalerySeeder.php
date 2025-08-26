<?php

namespace Database\Seeders;

use App\Models\Galery;
use App\Models\Post;
use Illuminate\Database\Seeder;

class GalerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $posts = Post::all();

        foreach ($posts as $index => $post) {
            // Create 1-3 galleries for each post
            $galleryCount = rand(1, 3);
            
            for ($i = 1; $i <= $galleryCount; $i++) {
                Galery::create([
                    'post_id' => $post->id,
                    'position' => $i,
                    'status' => 'active'
                ]);
            }
        }
    }
}
