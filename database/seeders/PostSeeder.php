<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Contoh berita untuk halaman /berita.
 *
 * Isi tulisan ini hanya contoh. Kalau sudah ada berita asli, ganti isinya di sini
 * lalu jalankan `php artisan db:seed --class=PostSeeder`, atau kelola berita
 * langsung dari Panel > Berita.
 */
class PostSeeder extends Seeder
{
    public function run(): void
    {
        $author = User::query()->where('email', UserSeeder::ADMIN_EMAIL)->firstOrFail();

        foreach ($this->posts() as $post) {
            Post::query()->updateOrCreate(
                ['slug' => Str::slug($post['title'])],
                [
                    'user_id' => $author->getKey(),
                    'title' => $post['title'],
                    'excerpt' => $post['excerpt'],
                    'body' => $post['body'],
                    'published_at' => $post['published_at'],
                ],
            );
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function posts(): array
    {
        return [
            [
                'title' => 'AbsenMu, presensi sidik jari yang sampai ke laporan',
                'excerpt' => 'Alat presensi berbasis ESP32 dan sensor sidik jari, dihubungkan ke dashboard yang dipakai guru dan admin sekolah setiap hari.',
                'published_at' => now()->subDays(4),
                'body' => <<<'MARKDOWN'
                    ## Masalah yang kami lihat

                    Presensi manual memakan waktu di awal jam pelajaran. Guru harus menyebut nama satu per satu, mencatat yang tidak hadir, lalu memindahkan catatan itu ke rekap. Pekerjaan kecil, tetapi dikerjakan berkali-kali setiap hari.

                    ## Cara alat ini bekerja

                    Alat hanya punya tiga tugas. Menempelkan jari, mencocokkan pola sidik jari dengan data yang tersimpan, lalu mengirim hasilnya ke server.

                    1. Siswa menempelkan jari pada sensor.
                    2. Alat mengirim nomor induk ke API Laravel.
                    3. Server mencatat kehadiran pada sesi jam pelajaran yang sedang dibuka guru.

                    Guru tidak perlu menunggu di depan pintu. Rekap kelas langsung terisi, dan admin bisa mengunduh laporannya.

                    ## Keputusan yang kami ambil

                    Presensi digantungkan pada sesi yang dibuka guru, bukan pada perbandingan jam server. Alasannya sederhana, jadwal sekolah sering bergeser. Kalau guru menggeser jam pelajaran, sesi absennya ikut bergeser tanpa perlu mengubah pengaturan apa pun.

                    Setiap balasan server juga dirancang untuk dibaca di layar alat yang hanya selebar 16 karakter. Ketika sensor belum didaftarkan atau sedang dimatikan, alat menampilkan pesan singkat yang jelas, bukan pesan galat.

                    ## Selanjutnya

                    Kami sedang merapikan laporan per kelas dan ringkasan kehadiran bulanan supaya wali kelas tidak perlu menghitung manual lagi.
                    MARKDOWN,
            ],
            [
                'title' => 'Kenapa kami memilih ESP32 untuk alat di lapangan',
                'excerpt' => 'Mikrokontroler murah yang sudah punya WiFi, cukup kuat mengurus sensor, dan mudah diprogram ulang dari jarak jauh.',
                'published_at' => now()->subDays(13),
                'body' => <<<'MARKDOWN'
                    ## Satu papan untuk banyak kebutuhan

                    Alat kami umumnya perlu tiga hal: membaca sensor, mengirim data ke server, dan menampilkan keadaan ke pengguna di sekitarnya. ESP32 sudah menyediakan ketiganya dalam satu papan, termasuk WiFi.

                    Untuk alat presensi di sekolah, itu artinya kami tidak perlu menambah modul jaringan, tidak perlu kabel LAN ke setiap ruangan, dan tidak perlu komputer kecil yang menyala terus.

                    ## Yang kami perhatikan saat merakit

                    Beberapa catatan yang selalu muncul dan terbukti menghemat waktu:

                    1. Pisahkan pin I2C dari pin yang dipakai lampu indikator dan tombol. Di beberapa papan, pin bawaan bertabrakan dengan keduanya.
                    2. Kirim data dalam satu muatan JSON supaya balasan server bisa diperiksa sekaligus.
                    3. Batasi panjang teks untuk layar alat. Layar LCD memotong teks yang terlalu panjang tanpa memberi peringatan apa pun.

                    ## Komunikasi dengan server

                    Alat berbicara dengan API Laravel memakai HTTP biasa. Untuk data yang perlu dikirim terus-menerus, kami memakai MQTT supaya koneksinya ringan dan alat bisa diberi tahu saat ada perubahan.

                    ## Biaya dan perawatan

                    Bagian termahal dari sebuah alat biasanya bukan papannya, melainkan waktu untuk merakit dan mengujinya. Memilih papan yang mudah diprogram ulang membuat perbaikan di lapangan jauh lebih cepat.
                    MARKDOWN,
            ],
            [
                'title' => 'Merancang website sekolah yang benar-benar dipakai',
                'excerpt' => 'Website sekolah bukan hanya pajangan. Kalau dikelola dengan alur yang benar, ia menjadi pusat informasi dan pendaftaran.',
                'published_at' => now()->subDays(26),
                'body' => <<<'MARKDOWN'
                    ## Mulai dari siapa pemakainya

                    Ada tiga kelompok yang membuka website sekolah: calon siswa dan orang tua, siswa dan guru yang sedang belajar, serta pengunjung yang mencari informasi resmi. Setiap kelompok mencari hal yang berbeda, dan halaman depan harus bisa mengarahkan ketiganya tanpa membuat bingung.

                    ## Yang sering terlewat

                    Bagian yang paling sering terlewat bukan halaman depan, melainkan cara mengisinya. Kalau admin harus menghubungi developer setiap kali ada berita baru, website akan berhenti diperbarui dalam beberapa bulan.

                    Karena itu kami mengerjakan panel admin lebih dulu:

                    1. Berita dan pengumuman bisa ditulis dan diterbitkan sendiri.
                    2. Data jurusan, guru, dan fasilitas dikelola dari satu tempat.
                    3. Teks di halaman depan bisa diganti tanpa menyentuh kode.

                    ## Tampilan yang tenang

                    Kami memilih tampilan sederhana: huruf yang jelas, jarak yang lega, dan sedikit warna. Halaman yang tenang membuat informasi mudah dibaca, terutama di ponsel dengan koneksi lambat.

                    ## Hasilnya

                    Website yang dikelola langsung oleh sekolah, dengan halaman profil, jurusan, berita, dan pendaftaran yang saling terhubung.
                    MARKDOWN,
            ],
            [
                'title' => 'Catatan merakit alat pertama kami',
                'excerpt' => 'Draf tulisan tentang kesalahan wiring pertama, sensor yang salah dipasang, dan pelajaran yang kami bawa sampai sekarang.',
                'published_at' => null,
                'body' => <<<'MARKDOWN'
                    ## Draf

                    Tulisan ini masih disusun. Bagian yang sudah siap:

                    - urutan langkah merakit alat
                    - daftar kesalahan yang paling sering terjadi
                    - cara menguji alat sebelum dipasang di lapangan

                    Bagian ini akan diterbitkan setelah alat generasi berikutnya selesai diuji.
                    MARKDOWN,
            ],
        ];
    }
}
