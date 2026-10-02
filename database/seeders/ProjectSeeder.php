<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\Project;
use App\ProjectCategory;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Seed the portfolio projects and link them to members.
     */
    public function run(): void
    {
        $members = Member::query()->pluck('id', 'slug');

        foreach ($this->projects() as $data) {
            $team = $data['team'];
            unset($data['team']);

            $project = Project::updateOrCreate(['slug' => $data['slug']], $data);

            $project->members()->sync(
                collect($team)
                    ->filter(fn (string $contribution, string $slug): bool => $members->has($slug))
                    ->mapWithKeys(fn (string $contribution, string $slug): array => [
                        $members[$slug] => ['contribution' => $contribution],
                    ])
                    ->all()
            );
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function projects(): array
    {
        return [
            [
                'title' => 'TEKARA AbsenMu',
                'slug' => 'tekara-absenmu',
                'summary' => 'Presensi sidik jari untuk sekolah: alat ESP32 di gerbang dan kelas, data langsung masuk ke dashboard.',
                'description' => 'Sistem presensi lengkap dari alat sampai laporan. Alat berbasis ESP32 dan sensor sidik jari AS608 membaca jari siswa dan guru, mencocokkannya dengan template yang tersimpan di server, lalu menampilkan nama di layar LCD. Di sisi web, admin mendaftarkan alat, mengatur jadwal pelajaran, dan mengunduh rekap kehadiran. Guru dan siswa punya portal sendiri.',
                'categories' => [ProjectCategory::Website, ProjectCategory::Iot],
                'client' => 'Sekolah mitra',
                'year' => 2026,
                'tech_stack' => ['Laravel', 'ESP32', 'Sensor AS608', 'LCD 16x2', 'MySQL', 'Tailwind CSS'],
                'highlights' => [
                    'Alat baru terdeteksi otomatis dan baru bisa dipakai setelah didaftarkan admin.',
                    'Presensi per jam pelajaran dengan portal guru dan siswa.',
                    'Laporan kehadiran dengan unduhan CSV dan versi cetak.',
                    'Layar alat menampilkan pesan dari server, misalnya saat alat dimatikan.',
                ],
                'url' => null,
                'is_featured' => true,
                'sort_order' => 1,
                'team' => [
                    'hari-poppy-latip' => 'Firmware ESP32, API, dan dashboard',
                    'yusuf-sardani' => 'Administrasi dan dokumentasi',
                    'zaskia-nabila' => 'Relasi klien dan pengembangan bisnis',
                    'mufidah-kholilah-putri' => 'Manajemen proyek dan analisis sistem',
                ],
            ],
            [
                'title' => 'Website SMK Istiqomah Muhammadiyah 4',
                'slug' => 'website-smkim4',
                'summary' => 'Website sekolah dengan profil, jurusan, berita, dan pendaftaran siswa baru yang dikelola dari panel admin.',
                'description' => 'Website resmi sekolah yang menggantikan halaman statis lama. Isi seperti berita, jurusan, keunggulan, dan fasilitas dikelola admin tanpa menyentuh kode. Navigasi dibuat nyaman di ponsel karena sebagian besar pengunjung adalah calon siswa dan orang tua.',
                'categories' => [ProjectCategory::Website],
                'client' => 'SMK Istiqomah Muhammadiyah 4 Samarinda',
                'year' => 2026,
                'tech_stack' => ['Laravel', 'Tailwind CSS', 'MySQL', 'TinyMCE'],
                'highlights' => [
                    'Panel admin untuk berita, jurusan, dan fasilitas.',
                    'Halaman SPMB untuk pendaftaran siswa baru.',
                    'Navigasi bawah khusus ponsel.',
                ],
                'url' => null,
                'is_featured' => true,
                'sort_order' => 2,
                'team' => [
                    'hari-poppy-latip' => 'Pengembangan penuh',
                    'zaskia-nabila' => 'Relasi klien dan pengembangan bisnis',
                ],
            ],
            [
                'title' => 'Portal Komunitas Pemuda Akhir Zaman',
                'slug' => 'portal-pemuda-akhir-zaman',
                'summary' => 'Portal kelompok mahasiswa untuk absen QR, jadwal, tugas, kas, dan galeri foto dari Google Drive.',
                'description' => 'Satu tempat untuk kegiatan komunitas. Absensi memakai kode QR yang berganti otomatis supaya tidak bisa dititipkan, kas kelompok tercatat rapi, dan galeri foto tersinkron langsung dari folder Google Drive.',
                'categories' => [ProjectCategory::Website],
                'client' => 'Komunitas mahasiswa',
                'year' => 2026,
                'tech_stack' => ['Laravel', 'MySQL', 'Google Drive API', 'MQTT', 'Bootstrap'],
                'highlights' => [
                    'Absensi QR dengan kode yang berganti berkala.',
                    'Galeri foto dan video dari Google Drive.',
                    'Pencatatan kas kelompok.',
                ],
                'url' => null,
                'is_featured' => false,
                'sort_order' => 3,
                'team' => [
                    'hari-poppy-latip' => 'Pengembangan penuh',
                    'mufidah-kholilah-putri' => 'Analisis sistem',
                ],
            ],
            [
                'title' => 'Piket Guru',
                'slug' => 'piket-guru',
                'summary' => 'Dashboard piket guru untuk memantau kelas, guru mengajar, dan siswa yang keluar kelas.',
                'description' => 'Aplikasi yang dipakai petugas piket dari ponsel. Petugas mencatat kondisi kelas, kehadiran guru, serta siswa yang izin keluar beserta alasan dan fotonya. Admin melihat riwayat dan laporan harian.',
                'categories' => [ProjectCategory::Website],
                'client' => 'Sekolah mitra',
                'year' => 2026,
                'tech_stack' => ['Laravel', 'Tailwind CSS', 'MySQL'],
                'highlights' => [
                    'Dirancang untuk dipakai cepat dari ponsel.',
                    'Riwayat dan laporan kegiatan piket.',
                ],
                'url' => null,
                'is_featured' => false,
                'sort_order' => 4,
                'team' => [
                    'hari-poppy-latip' => 'Backend',
                    'zaskia-nabila' => 'Relasi klien',
                ],
            ],
            [
                'title' => 'Pencatat Suhu dan Kelembapan',
                'slug' => 'pencatat-suhu-kelembapan',
                'summary' => 'Alat ESP32 yang mencatat suhu dan kelembapan ke kartu microSD lengkap dengan tanggal dan jam dari internet.',
                'description' => 'Alat pencatat lingkungan yang bisa ditinggal berhari-hari. Sensor DHT22 dibaca berkala, waktu diambil dari internet, lalu data disimpan sebagai CSV di kartu microSD sehingga mudah dibuka di Excel. Layar LCD menampilkan nilai terkini.',
                'categories' => [ProjectCategory::Iot],
                'client' => null,
                'year' => 2026,
                'tech_stack' => ['ESP32', 'DHT22', 'microSD', 'LCD I2C', 'NTP'],
                'highlights' => [
                    'Data CSV siap dibuka di Excel.',
                    'Cap waktu otomatis dari internet.',
                ],
                'url' => null,
                'is_featured' => false,
                'sort_order' => 5,
                'team' => [
                    'mufidah-kholilah-putri' => 'Analisis sistem',
                    'hari-poppy-latip' => 'Firmware',
                ],
            ],
            [
                'title' => 'Monitor Daya Baterai',
                'slug' => 'monitor-daya-baterai',
                'summary' => 'Pengukur tegangan, arus, dan daya baterai 12 V yang mengirim data ke broker MQTT.',
                'description' => 'Modul pemantau daya berbasis ESP32 dan sensor INA219. Tegangan sudah dikalibrasi terhadap multimeter, pembacaan arus kecil diredam supaya tidak bergoyang, dan data dikirim lewat MQTT agar bisa dipantau dari jauh.',
                'categories' => [ProjectCategory::Iot],
                'client' => null,
                'year' => 2026,
                'tech_stack' => ['ESP32', 'INA219', 'MQTT', 'PlatformIO'],
                'highlights' => [
                    'Kalibrasi tegangan terhadap alat ukur acuan.',
                    'Peringatan otomatis saat pembacaan tidak stabil.',
                ],
                'url' => null,
                'is_featured' => false,
                'sort_order' => 6,
                'team' => [
                    'mufidah-kholilah-putri' => 'Analisis sistem',
                    'hari-poppy-latip' => 'Firmware',
                ],
            ],
            [
                'title' => 'Stasiun Multi-Sensor',
                'slug' => 'stasiun-multi-sensor',
                'summary' => 'ESP32-S3 yang membaca daya, gerak, suhu, dan cahaya sekaligus dari satu jalur I2C.',
                'description' => 'Papan pengujian untuk proyek pemantauan. Empat sensor dibaca bersamaan: INA219 untuk daya, MPU6050 untuk gerak, DHT22 untuk suhu dan kelembapan, dan BH1750 untuk intensitas cahaya. Sensor yang terlepas terdeteksi dan dicari ulang otomatis.',
                'categories' => [ProjectCategory::Iot],
                'client' => null,
                'year' => 2026,
                'tech_stack' => ['ESP32-S3', 'INA219', 'MPU6050', 'DHT22', 'BH1750'],
                'highlights' => [
                    'Tiap sensor berdiri sendiri, satu sensor rusak tidak menghentikan yang lain.',
                    'Deteksi alamat I2C otomatis saat menyala.',
                ],
                'url' => null,
                'is_featured' => false,
                'sort_order' => 7,
                'team' => [
                    'mufidah-kholilah-putri' => 'Analisis sistem',
                    'hari-poppy-latip' => 'Firmware',
                ],
            ],
        ];
    }
}
