<?php

namespace Database\Seeders;

use App\Models\Member;
use Illuminate\Database\Seeder;

class MemberSeeder extends Seeder
{
    /**
     * Seed the four Tekara members.
     *
     * Data anggota 2 sampai 4 masih contoh. Ganti nama, peran, dan isi CV
     * di sini lalu jalankan `php artisan db:seed --class=MemberSeeder`.
     */
    public function run(): void
    {
        foreach ($this->members() as $member) {
            Member::updateOrCreate(['slug' => $member['slug']], $member);
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function members(): array
    {
        return [
            [
                'name' => 'Hari Poppy Latip',
                'slug' => 'hari-poppy-latip',
                'role' => 'Web & IoT Developer',
                'headline' => 'Membangun sistem yang menghubungkan alat di lapangan dengan dashboard di browser.',
                'summary' => 'Developer Laravel dan PHP yang juga menulis firmware ESP32. Terbiasa mengerjakan satu sistem dari ujung ke ujung: sensor sidik jari dan LCD di alat, API yang menerima datanya, sampai halaman laporan yang dipakai guru dan admin sekolah.',
                'location' => 'Samarinda, Kalimantan Timur',
                'email' => null,
                'phone' => null,
                'links' => [
                    ['label' => 'GitHub', 'url' => 'https://github.com/'],
                    ['label' => 'LinkedIn', 'url' => 'https://www.linkedin.com/'],
                ],
                'skills' => [
                    ['group' => 'Web', 'items' => ['Laravel', 'PHP', 'Tailwind CSS', 'MySQL', 'REST API']],
                    ['group' => 'IoT', 'items' => ['ESP32', 'PlatformIO', 'Sensor I2C dan UART', 'MQTT']],
                    ['group' => 'Lainnya', 'items' => ['Git', 'Google Drive API', 'Desain database']],
                ],
                'experiences' => [
                    [
                        'title' => 'Web & IoT Developer',
                        'place' => 'Tekara',
                        'period' => '2026 - sekarang',
                        'description' => 'Merancang sistem presensi sidik jari TEKARA AbsenMu, dari firmware ESP32 dan sensor AS608 sampai dashboard Laravel dengan laporan dan unduhan CSV.',
                    ],
                    [
                        'title' => 'Web Developer (PKL)',
                        'place' => 'SMK Istiqomah Muhammadiyah 4 Samarinda',
                        'period' => '2026',
                        'description' => 'Membangun website sekolah dengan Laravel dan Tailwind CSS: beranda, jurusan, berita, profil, dan halaman SPMB yang dikelola dari panel admin.',
                    ],
                ],
                'educations' => [
                    ['school' => 'Universitas Muhammadiyah Kalimantan Timur', 'major' => 'Informatika', 'period' => 'Sekarang'],
                ],
                'certifications' => [],
                'sort_order' => 1,
            ],
            [
                'name' => 'Rizky Pratama',
                'slug' => 'rizky-pratama',
                'role' => 'Backend Developer',
                'headline' => 'Merancang API dan database yang rapi supaya sistem mudah dikembangkan.',
                'summary' => 'Fokus pada sisi server: struktur database, API untuk perangkat IoT, otentikasi, dan laporan. Senang membuat kode yang mudah dibaca rekan satu tim.',
                'location' => 'Samarinda, Kalimantan Timur',
                'email' => null,
                'phone' => null,
                'links' => [],
                'skills' => [
                    ['group' => 'Backend', 'items' => ['Laravel', 'PHP', 'MySQL', 'REST API']],
                    ['group' => 'Tools', 'items' => ['Git', 'Postman', 'Linux server']],
                ],
                'experiences' => [
                    [
                        'title' => 'Backend Developer',
                        'place' => 'Tekara',
                        'period' => '2026 - sekarang',
                        'description' => 'Contoh isi. Tuliskan tanggung jawab dan hasil kerja di sini.',
                    ],
                ],
                'educations' => [
                    ['school' => 'Nama kampus atau sekolah', 'major' => 'Jurusan', 'period' => 'Tahun'],
                ],
                'certifications' => [],
                'sort_order' => 2,
            ],
            [
                'name' => 'Nadia Putri',
                'slug' => 'nadia-putri',
                'role' => 'UI/UX & Frontend Developer',
                'headline' => 'Membuat tampilan yang tenang, jelas, dan mudah dipakai semua umur.',
                'summary' => 'Mengubah kebutuhan pengguna menjadi alur dan tampilan yang sederhana. Menulis antarmuka dengan Blade dan Tailwind CSS serta memastikan tampilannya nyaman di ponsel.',
                'location' => 'Samarinda, Kalimantan Timur',
                'email' => null,
                'phone' => null,
                'links' => [],
                'skills' => [
                    ['group' => 'Desain', 'items' => ['Figma', 'UI/UX', 'Design system']],
                    ['group' => 'Frontend', 'items' => ['Tailwind CSS', 'JavaScript', 'Blade']],
                ],
                'experiences' => [
                    [
                        'title' => 'UI/UX & Frontend Developer',
                        'place' => 'Tekara',
                        'period' => '2026 - sekarang',
                        'description' => 'Contoh isi. Tuliskan tanggung jawab dan hasil kerja di sini.',
                    ],
                ],
                'educations' => [
                    ['school' => 'Nama kampus atau sekolah', 'major' => 'Jurusan', 'period' => 'Tahun'],
                ],
                'certifications' => [],
                'sort_order' => 3,
            ],
            [
                'name' => 'Dimas Saputra',
                'slug' => 'dimas-saputra',
                'role' => 'IoT & Hardware Engineer',
                'headline' => 'Merakit dan memprogram alat yang tetap bekerja di kondisi nyata.',
                'summary' => 'Menangani rangkaian, pemilihan sensor, dan firmware mikrokontroler. Terbiasa menguji alat di lapangan dan mencari penyebab masalah dari wiring sampai protokol komunikasi.',
                'location' => 'Samarinda, Kalimantan Timur',
                'email' => null,
                'phone' => null,
                'links' => [],
                'skills' => [
                    ['group' => 'Hardware', 'items' => ['ESP32', 'Desain rangkaian', 'Sensor dan aktuator']],
                    ['group' => 'Firmware', 'items' => ['C++ Arduino', 'PlatformIO', 'MQTT']],
                ],
                'experiences' => [
                    [
                        'title' => 'IoT & Hardware Engineer',
                        'place' => 'Tekara',
                        'period' => '2026 - sekarang',
                        'description' => 'Contoh isi. Tuliskan tanggung jawab dan hasil kerja di sini.',
                    ],
                ],
                'educations' => [
                    ['school' => 'Nama kampus atau sekolah', 'major' => 'Jurusan', 'period' => 'Tahun'],
                ],
                'certifications' => [],
                'sort_order' => 4,
            ],
        ];
    }
}
