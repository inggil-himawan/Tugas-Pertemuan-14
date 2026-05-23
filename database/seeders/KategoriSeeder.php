<?php
 
namespace Database\Seeders;
 
use Illuminate\Database\Seeder;
use App\Models\Kategori;
 
class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategoriList = [
           [
                'nama_kategori' => 'Programming',
                'deskripsi' => 'Kategori yang membahas seputar bahasa pemrograman dan logika coding.',
                'icon' => 'code-slash',
                'warna' => 'primary',
            ],
            [
                'nama_kategori' => 'Database',
                'deskripsi' => 'Kategori yang membahas penyimpanan data, SQL, NoSQL, dan optimasi query.',
                'icon' => 'database',
                'warna' => 'success',
            ],
            [
                'nama_kategori' => 'Web Design',
                'deskripsi' => 'Kategori tentang perancangan visual website, UI/UX, CSS, dan layouting.',
                'icon' => 'palette',
                'warna' => 'info',
            ],
            [
                'nama_kategori' => 'Networking',
                'deskripsi' => 'Kategori mengenai jaringan komputer, protokol, keamanan, dan konfigurasi server.',
                'icon' => 'wifi',
                'warna' => 'warning',
            ],
            [
                'nama_kategori' => 'Data Science',
                'deskripsi' => 'Kategori tentang analisis data, machine learning, statistik, dan visualisasi data.',
                'icon' => 'graph-up',
                'warna' => 'danger',
            ],
        ];
 
        foreach ($kategoriList as $kategori) {
            Kategori::create($kategori);
        }
    }
}
