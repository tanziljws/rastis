<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategoris = [
            'Informasi Terkini',
            'Galery Sekolah',
            'Agenda Sekolah'
        ];

        foreach ($kategoris as $judul) {
            Kategori::create([
                'judul' => $judul
            ]);
        }
    }
}
