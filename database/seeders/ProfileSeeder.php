<?php

namespace Database\Seeders;

use App\Models\Profile;
use Illuminate\Database\Seeder;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $profiles = [
            [
                'judul' => 'Visi Sekolah',
                'isi' => 'Menjadi sekolah unggulan yang menghasilkan lulusan berkualitas, berakhlak mulia, dan siap menghadapi tantangan global dengan mengembangkan potensi siswa secara optimal melalui pendidikan yang berkualitas dan berwawasan lingkungan.'
            ],
            [
                'judul' => 'Misi Sekolah',
                'isi' => '1. Menyelenggarakan pendidikan yang berkualitas dan berwawasan global\n2. Mengembangkan potensi siswa secara optimal melalui kegiatan akademik dan non-akademik\n3. Menanamkan nilai-nilai karakter dan akhlak mulia\n4. Mewujudkan lingkungan sekolah yang nyaman, aman, dan kondusif\n5. Mengembangkan kerjasama dengan berbagai pihak untuk meningkatkan kualitas pendidikan'
            ],
            [
                'judul' => 'Sejarah Sekolah',
                'isi' => 'Sekolah ini didirikan pada tahun 1985 dengan visi untuk memberikan pendidikan berkualitas kepada masyarakat. Berawal dari gedung sederhana dengan beberapa ruang kelas, sekolah ini telah berkembang menjadi institusi pendidikan yang diakui keunggulannya di tingkat regional maupun nasional.'
            ]
        ];

        foreach ($profiles as $profileData) {
            Profile::create([
                'judul' => $profileData['judul'],
                'isi' => $profileData['isi']
            ]);
        }
    }
}
