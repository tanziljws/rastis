<?php

namespace Database\Seeders;

use App\Models\Foto;
use App\Models\Galery;
use Illuminate\Database\Seeder;

class FotoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $galeries = Galery::all();

        $sampleFotoNames = [
            'kegiatan_belajar_1.jpg',
            'kegiatan_belajar_2.jpg',
            'pramuka_1.jpg',
            'pramuka_2.jpg',
            'ekstrakurikuler_1.jpg',
            'ekstrakurikuler_2.jpg',
            'upacara_bendera.jpg',
            'rapat_guru.jpg',
            'kunjungan_industri.jpg',
            'praktek_lab.jpg',
            'olahraga.jpg',
            'seni_budaya.jpg',
            'perpustakaan.jpg',
            'kantin_sekolah.jpg',
            'halaman_sekolah.jpg'
        ];

        foreach ($galeries as $galery) {
            // Create 2-5 photos for each gallery
            $photoCount = rand(2, 5);
            
            for ($i = 1; $i <= $photoCount; $i++) {
                $randomFotoName = $sampleFotoNames[array_rand($sampleFotoNames)];
                
                Foto::create([
                    'galery_id' => $galery->id,
                    'file' => $randomFotoName,
                    'judul' => 'Foto ' . $i . ' - ' . $galery->post->judul
                ]);
            }
        }
    }
}
