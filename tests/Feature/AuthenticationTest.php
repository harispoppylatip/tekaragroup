<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_is_visible_to_guests(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee('Masuk ke panel');
    }

    public function test_signed_in_users_are_kept_away_from_the_login_page(): void
    {
        $this->actingAs($this->memberUser())
            ->get(route('login'))
            ->assertRedirect(route('panel.dashboard'));
    }

    public function test_user_can_sign_in_with_the_right_credentials(): void
    {
        $user = User::factory()->admin()->create([
            'email' => 'admin@tekara.my.id',
            'password' => 'rahasia123',
        ]);

        $this->post(route('login.store'), [
            'email' => 'admin@tekara.my.id',
            'password' => 'rahasia123',
        ])->assertRedirect(route('panel.dashboard'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_wrong_password_is_rejected_and_the_message_is_visible(): void
    {
        User::factory()->create([
            'email' => 'admin@tekara.my.id',
            'password' => 'rahasia123',
        ]);

        $this->from(route('login'))
            ->followingRedirects()
            ->post(route('login.store'), [
                'email' => 'admin@tekara.my.id',
                'password' => 'sandi-yang-salah',
            ])
            ->assertSee('Email atau sandi tidak cocok.');

        $this->assertGuest();
    }

    public function test_user_on_the_default_password_must_change_it_first(): void
    {
        $user = User::factory()->mustChangePassword()->create();

        $this->actingAs($user)->get(route('panel.dashboard'))->assertRedirect(route('panel.password.edit'));
        $this->actingAs($user)->get(route('panel.projects.index'))->assertRedirect(route('panel.password.edit'));
        $this->actingAs($user)->get(route('panel.posts.index'))->assertRedirect(route('panel.password.edit'));
        $this->actingAs($user)->get(route('panel.password.edit'))->assertOk();
    }

    public function test_changing_the_password_clears_the_forced_flag(): void
    {
        $user = User::factory()->mustChangePassword()->create([
            'password' => config('tekara.default_password'),
        ]);

        $this->actingAs($user)
            ->put(route('panel.password.update'), [
                'current_password' => config('tekara.default_password'),
                'password' => 'SandiBaru2026!',
                'password_confirmation' => 'SandiBaru2026!',
            ])
            ->assertRedirect(route('panel.dashboard'));

        $user->refresh();

        $this->assertFalse($user->must_change_password);
        $this->assertTrue(Hash::check('SandiBaru2026!', $user->password));
        $this->actingAs($user)->get(route('panel.dashboard'))->assertOk();
    }

    public function test_new_password_may_not_match_the_default_password(): void
    {
        $user = User::factory()->mustChangePassword()->create([
            'password' => config('tekara.default_password'),
        ]);

        $this->actingAs($user)
            ->from(route('panel.password.edit'))
            ->followingRedirects()
            ->put(route('panel.password.update'), [
                'current_password' => config('tekara.default_password'),
                'password' => config('tekara.default_password'),
                'password_confirmation' => config('tekara.default_password'),
            ])
            ->assertSee('Sandi baru tidak boleh sama dengan sandi awal.');

        $user->refresh();

        $this->assertTrue($user->must_change_password);
    }

    public function test_user_can_sign_out(): void
    {
        $this->actingAs($this->memberUser())
            ->post(route('logout'))
            ->assertRedirect(route('home'));

        $this->assertGuest();
    }

    private function memberUser(): User
    {
        return User::factory()->create(['must_change_password' => false]);
    }
}
