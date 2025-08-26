<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Kategori;
use App\Models\Petugas;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategoris = Kategori::all();
        $petugas = Petugas::first();

        $samplePosts = [
            [
                'judul' => 'Pembukaan Tahun Ajaran Baru 2024/2025',
                'kategori_id' => $kategoris->where('judul', 'Informasi Terkini')->first()->id,
                'isi' => 'Selamat datang di tahun ajaran baru 2024/2025. Semoga semua siswa dan guru dapat menjalankan kegiatan belajar mengajar dengan semangat dan dedikasi yang tinggi.',
                'status' => 'published'
            ],
            [
                'judul' => 'Kegiatan Pramuka Minggu Ini',
                'kategori_id' => $kategoris->where('judul', 'Agenda Sekolah')->first()->id,
                'isi' => 'Kegiatan pramuka akan dilaksanakan pada hari Sabtu pukul 07.00 WIB. Semua anggota pramuka wajib hadir dengan seragam lengkap.',
                'status' => 'published'
            ],
            [
                'judul' => 'Foto Kegiatan Belajar Mengajar',
                'kategori_id' => $kategoris->where('judul', 'Galery Sekolah')->first()->id,
                'isi' => 'Berikut adalah dokumentasi kegiatan belajar mengajar di kelas yang menunjukkan antusiasme siswa dalam menimba ilmu.',
                'status' => 'published'
            ],
            [
                'judul' => 'Jadwal Ujian Tengah Semester',
                'kategori_id' => $kategoris->where('judul', 'Informasi Terkini')->first()->id,
                'isi' => 'Ujian Tengah Semester akan dilaksanakan pada tanggal 15-20 Oktober 2024. Semua siswa diharapkan mempersiapkan diri dengan baik.',
                'status' => 'published'
            ],
            [
                'judul' => 'Kegiatan Ekstrakurikuler Seni',
                'kategori_id' => $kategoris->where('judul', 'Agenda Sekolah')->first()->id,
                'isi' => 'Ekstrakurikuler seni akan mengadakan pertunjukan pada akhir bulan ini. Semua siswa yang berminat dapat mendaftar di ruang guru.',
                'status' => 'draft'
            ]
        ];

        foreach ($samplePosts as $postData) {
            Post::create([
                'judul' => $postData['judul'],
                'kategori_id' => $postData['kategori_id'],
                'isi' => $postData['isi'],
                'petugas_id' => $petugas->id,
                'status' => $postData['status']
            ]);
        }
    }
}
