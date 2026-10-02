<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\User;
use App\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PanelProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_account_can_open_their_own_profile_page(): void
    {
        $admin = User::factory()->admin()->create(['must_change_password' => false]);

        $this->actingAs($admin)
            ->get(route('panel.profile.edit'))
            ->assertOk()
            ->assertSee('Profil saya')
            ->assertSee($admin->email)
            ->assertSee('Belum tampil di halaman tim');
    }

    public function test_member_can_open_their_own_profile_page(): void
    {
        $member = Member::factory()->create();
        $user = User::factory()->create(['must_change_password' => false]);
        $member->user()->associate($user)->save();

        $this->actingAs($user)
            ->get(route('panel.profile.edit'))
            ->assertOk()
            ->assertSee('Profil yang tampil di website');
    }

    public function test_account_can_change_their_own_name_and_email(): void
    {
        $user = User::factory()->create(['must_change_password' => false]);

        $this->actingAs($user)
            ->from(route('panel.profile.edit'))
            ->followingRedirects()
            ->put(route('panel.profile.update'), ['name' => 'Haris Tekara', 'email' => 'haris@tekara.my.id'])
            ->assertSee('Profil Anda disimpan.');

        $user->refresh();

        $this->assertSame('Haris Tekara', $user->name);
        $this->assertSame('haris@tekara.my.id', $user->email);
    }

    public function test_email_must_not_be_used_by_another_account(): void
    {
        $user = User::factory()->create(['must_change_password' => false]);
        $other = User::factory()->create();

        $this->actingAs($user)
            ->put(route('panel.profile.update'), ['name' => $user->name, 'email' => $other->email])
            ->assertSessionHasErrors('email');

        $this->assertSame($user->email, $user->refresh()->email);
    }

    public function test_account_can_upload_and_remove_their_own_photo(): void
    {
        Storage::fake('public');

        $user = User::factory()->admin()->create(['must_change_password' => false]);

        $this->actingAs($user)
            ->put(route('panel.profile.update'), [
                'name' => $user->name,
                'email' => $user->email,
                'photo' => UploadedFile::fake()->image('foto.png', 40, 40),
            ])
            ->assertRedirect(route('panel.profile.edit'));

        $photo = $user->refresh()->photo;

        $this->assertNotNull($photo);
        $this->assertStringStartsWith('avatars/', $photo);
        $this->assertTrue(Storage::disk('public')->exists($photo));

        $this->actingAs($user)
            ->get(route('panel.profile.edit'))
            ->assertSee($photo);

        $this->actingAs($user)
            ->put(route('panel.profile.update'), [
                'name' => $user->name,
                'email' => $user->email,
                'remove_photo' => '1',
            ])
            ->assertRedirect(route('panel.profile.edit'));

        $this->assertNull($user->refresh()->photo);
        $this->assertFalse(Storage::disk('public')->exists($photo));
    }

    public function test_account_cannot_change_their_own_role_from_the_profile_form(): void
    {
        $user = User::factory()->create(['must_change_password' => false]);

        $this->actingAs($user)
            ->put(route('panel.profile.update'), [
                'name' => $user->name,
                'email' => $user->email,
                'role' => UserRole::Admin->value,
            ])
            ->assertRedirect(route('panel.profile.edit'));

        $this->assertSame(UserRole::Member, $user->refresh()->role);
    }

    public function test_guest_cannot_open_the_profile_page(): void
    {
        $this->get(route('panel.profile.edit'))->assertRedirect(route('login'));
    }
}
