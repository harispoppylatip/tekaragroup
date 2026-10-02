<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Sandi awal akun anggota
    |--------------------------------------------------------------------------
    |
    | Dipakai saat admin menambah anggota atau mengatur ulang sandi. Pemilik
    | akun wajib menggantinya saat pertama kali masuk.
    |
    */

    'default_password' => env('TEKARA_DEFAULT_PASSWORD', 'tekara123'),

    /*
    |--------------------------------------------------------------------------
    | Nilai awal pengaturan website
    |--------------------------------------------------------------------------
    |
    | Dipakai sampai admin menyimpan nilai lain di Panel > Pengaturan.
    |
    */

    'settings' => [
        'site_tagline' => 'Teknologi, Karya, Rancang',
        'hero_title' => 'Dari ide menjadi karya yang benar-benar dipakai.',
        'hero_text' => 'Tekara adalah tim teknologi yang merancang website dan perangkat IoT. Kami mengerjakan semuanya, mulai dari sensor di lapangan sampai dashboard di browser.',
        'meta_description' => 'Tekara merancang website dan perangkat IoT, dari sensor di lapangan sampai dashboard di browser.',
        'footer_text' => 'Teknologi, karya, rancang. Kami membangun website dan perangkat IoT yang dipakai sehari-hari oleh sekolah, komunitas, dan usaha.',
        'contact_title' => 'Punya ide yang ingin diwujudkan?',
        'contact_text' => 'Ceritakan kebutuhan Anda, baik website, alat IoT, maupun gabungan keduanya. Kami bantu dari rancangan pertama sampai sistemnya berjalan.',
        'contact_email' => env('TEKARA_EMAIL'),
        'contact_whatsapp' => env('TEKARA_WHATSAPP'),
        'contact_instagram' => env('TEKARA_INSTAGRAM'),
        'contact_location' => env('TEKARA_LOCATION', 'Samarinda, Kalimantan Timur'),
    ],

];
