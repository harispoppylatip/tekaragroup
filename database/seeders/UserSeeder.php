<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\User;
use App\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Membuat akun login admin dan akun untuk setiap anggota.
 *
 * Semua akun baru memakai sandi awal dari config/tekara.php (bawaan: tekara123)
 * dan wajib diganti saat pertama kali masuk.
 *
 * Akun yang sudah ada tidak diubah, jadi seeder ini aman dijalankan berulang kali.
 */
class UserSeeder extends Seeder
{
    /**
     * Akun admin bawaan, dipakai juga oleh PostSeeder sebagai penulis contoh.
     */
    public const ADMIN_EMAIL = 'admin@tekara.my.id';

    public function run(): void
    {
        $this->createAdmin();
        $this->createMemberAccounts();
    }

    private function createAdmin(): void
    {
        User::query()->firstOrCreate(['email' => self::ADMIN_EMAIL], [
            'name' => 'Admin Tekara',
            'password' => config('tekara.default_password'),
            'role' => UserRole::Admin,
            'must_change_password' => true,
        ]);
    }

    /**
     * Hubungkan setiap anggota yang belum punya akun dengan akun baru.
     */
    private function createMemberAccounts(): void
    {
        $members = Member::query()->whereNull('user_id')->orderBy('sort_order')->get();

        foreach ($members as $member) {
            $user = User::query()->firstOrCreate(['email' => $this->emailFor($member)], [
                'name' => $member->name,
                'password' => config('tekara.default_password'),
                'role' => UserRole::Member,
                'must_change_password' => true,
            ]);

            $member->user()->associate($user)->save();
        }
    }

    private function emailFor(Member $member): string
    {
        return Str::slug($member->slug).'@tekara.my.id';
    }
}
