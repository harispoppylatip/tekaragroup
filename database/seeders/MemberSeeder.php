<?php

namespace Database\Seeders;

use App\Models\Member;
use Illuminate\Database\Seeder;

class MemberSeeder extends Seeder
{
    /**
     * Seed the four current Tekara members.
     */
    public function run(): void
    {
        foreach ($this->members() as $memberData) {
            $member = Member::query()->firstOrNew(['slug' => $memberData['slug']]);

            if ($member->exists === false) {
                $member = Member::query()
                    ->whereIn('slug', $this->legacySlugsFor($memberData['slug']))
                    ->first() ?? $member;
            }

            $member->fill($memberData)->save();
        }
    }

    /**
     * @return array<int, string>
     */
    private function legacySlugsFor(string $slug): array
    {
        return [
            'yusuf-sardani' => ['rizky-pratama'],
            'zaskia-nabila' => ['nadia-putri'],
            'mufidah-kholilah-putri' => ['dimas-saputra'],
        ][$slug] ?? [];
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
                'role' => 'Lead Full-Stack & IoT Hardware Engineer',
                'email' => 'haristheking1@gmail.com',
                'phone' => '081321897866',
                'headline' => 'Membangun sistem yang menghubungkan alat di lapangan dengan dashboard di browser.',
                'summary' => 'Developer Laravel dan PHP yang juga menulis firmware ESP32. Terbiasa mengerjakan satu sistem dari ujung ke ujung: sensor sidik jari dan LCD di alat, API yang menerima datanya, sampai halaman laporan yang dipakai guru dan admin sekolah.',
                'location' => 'Samarinda, Kalimantan Timur',
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
                'name' => 'Yusuf Sardani',
                'slug' => 'yusuf-sardani',
                'role' => 'Administrative & Documentation Specialist',
                'headline' => 'Mengelola seluruh administrasi, perizinan, dan dokumen kontrak proyek agar berjalan lancar.',
                'summary' => 'Menangani urusan administrasi, penyusunan dokumen kerja sama, surat-menyurat, serta arsip data proyek. Memastikan setiap kesepakatan dan kebutuhan administratif tertata rapi secara hukum dan terstruktur untuk mendukung kelancaran operasional tim.',
                'location' => 'Samarinda, Kalimantan Timur',
                'email' => null,
                'phone' => null,
                'links' => [],
                'skills' => [
                    ['group' => 'Administrasi', 'items' => ['Penyusunan Kontrak', 'Pembuatan Surat Resmi', 'Kearsipan Dokumen', 'Manajemen Arsip']],
                ],
                'experiences' => [
                    [
                        'title' => 'Administrative & Documentation Specialist',
                        'place' => 'Tekara',
                        'period' => '2026 - sekarang',
                        'description' => 'Mengelola administrasi, dokumen kerja sama, surat-menyurat, dan arsip proyek untuk mendukung operasional tim.',
                    ],
                ],
                'educations' => [],
                'certifications' => [],
                'sort_order' => 2,
            ],
            [
                'name' => 'Zaskia Nabila',
                'slug' => 'zaskia-nabila',
                'role' => 'Business Development & Client Relations Manager',
                'headline' => 'Menghubungkan kebutuhan klien dengan tim untuk memastikan kerja sama berjalan sukses.',
                'summary' => 'Berperan sebagai garda depan yang menemui klien, menggali kebutuhan proyek, serta merundingkan penawaran kerja sama. Fokus membangun hubungan baik dan memastikan visi klien tersampaikan dengan jelas kepada tim teknis.',
                'location' => 'Samarinda, Kalimantan Timur',
                'email' => null,
                'phone' => null,
                'links' => [],
                'skills' => [
                    ['group' => 'Bisnis & Klien', 'items' => ['Negosiasi', 'Presentasi / Pitching', 'Manajemen Hubungan Klien', 'Riset Pasar']],
                    ['group' => 'Komunikasi', 'items' => ['Komunikasi Publik', 'Penawaran Kerja Sama', 'Analisis Kebutuhan Klien']],
                ],
                'experiences' => [
                    [
                        'title' => 'Business Development & Client Relations Manager',
                        'place' => 'Tekara',
                        'period' => '2026 - sekarang',
                        'description' => 'Menemui klien, menggali kebutuhan proyek, menyusun penawaran kerja sama, dan menyampaikan kebutuhan klien kepada tim teknis.',
                    ],
                ],
                'educations' => [],
                'certifications' => [],
                'sort_order' => 3,
            ],
            [
                'name' => 'Mufidah Kholilah Putri',
                'slug' => 'mufidah-kholilah-putri',
                'role' => 'Project Manager & Systems Analyst',
                'headline' => 'Menyusun alur proyek dari hasil wawancara klien dan menyelaraskan kerja tim.',
                'summary' => 'Menerjemahkan keinginan dan hasil diskusi klien menjadi cetak biru proyek yang terstruktur. Bertanggung jawab mengatur timeline pengerjaan serta mengoordinasikan sisi teknis dan administratif agar proyek selesai tepat sasaran.',
                'location' => 'Samarinda, Kalimantan Timur',
                'email' => null,
                'phone' => null,
                'links' => [],
                'skills' => [
                    ['group' => 'Manajemen Proyek', 'items' => ['Penjadwalan Proyek', 'Manajemen Tim', 'Quality Control', 'Pelacakan Tugas']],
                    ['group' => 'Analisis', 'items' => ['Analisis Sistem', 'Pembuatan Alur Kerja (Workflow)', 'Dokumentasi Teknis', 'Wawancara Klien']],
                ],
                'experiences' => [
                    [
                        'title' => 'Project Manager & Systems Analyst',
                        'place' => 'Tekara',
                        'period' => '2026 - sekarang',
                        'description' => 'Menyusun alur kerja dari hasil wawancara klien, mengatur timeline, dan mengoordinasikan tim teknis serta administratif.',
                    ],
                ],
                'educations' => [],
                'certifications' => [],
                'sort_order' => 4,
            ],
        ];
    }
}
