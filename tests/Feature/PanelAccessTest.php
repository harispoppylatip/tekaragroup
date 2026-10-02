<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PanelAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_sent_to_the_login_page(): void
    {
        $this->get(route('panel.dashboard'))->assertRedirect(route('login'));
        $this->get(route('panel.projects.index'))->assertRedirect(route('login'));
        $this->get(route('panel.members.index'))->assertRedirect(route('login'));
        $this->get(route('panel.settings.edit'))->assertRedirect(route('login'));
    }

    public function test_public_pages_stay_open_to_everyone(): void
    {
        $this->get(route('home'))->assertOk();
        $this->get(route('posts.index'))->assertOk();
        $this->get(route('login'))->assertOk();
    }

    public function test_member_can_open_the_shared_panel_pages(): void
    {
        $user = $this->memberWithProfile()->user;

        $this->actingAs($user)->get(route('panel.dashboard'))->assertOk();
        $this->actingAs($user)->get(route('panel.password.edit'))->assertOk();
        $this->actingAs($user)->get(route('panel.cv.edit'))->assertOk();
        $this->actingAs($user)->get(route('panel.projects.index'))->assertOk();
        $this->actingAs($user)->get(route('panel.projects.create'))->assertOk();
        $this->actingAs($user)->get(route('panel.posts.index'))->assertOk();
        $this->actingAs($user)->get(route('panel.posts.create'))->assertOk();
    }

    public function test_member_is_locked_out_of_admin_pages(): void
    {
        $user = $this->memberWithProfile()->user;

        $this->actingAs($user)->get(route('panel.members.index'))->assertForbidden();
        $this->actingAs($user)->get(route('panel.members.create'))->assertForbidden();
        $this->actingAs($user)->get(route('panel.settings.edit'))->assertForbidden();
        $this->actingAs($user)->put(route('panel.settings.update'), [])->assertForbidden();
    }

    public function test_admin_can_open_the_admin_pages(): void
    {
        $admin = User::factory()->admin()->create(['must_change_password' => false]);

        $this->actingAs($admin)->get(route('panel.members.index'))->assertOk();
        $this->actingAs($admin)->get(route('panel.members.create'))->assertOk();
        $this->actingAs($admin)->get(route('panel.settings.edit'))->assertOk();
        $this->actingAs($admin)->get(route('panel.dashboard'))->assertOk();
    }

    public function test_account_without_a_profile_cannot_add_or_edit_projects(): void
    {
        $user = User::factory()->create(['must_change_password' => false]);

        $this->actingAs($user)->get(route('panel.projects.create'))->assertForbidden();

        $this->actingAs($user)
            ->post(route('panel.projects.store'), [
                'title' => 'Proyek Titipan',
                'summary' => 'Ringkasan proyek.',
                'description' => 'Deskripsi proyek.',
                'categories' => ['website'],
                'year' => 2026,
            ])
            ->assertForbidden();

        $this->assertDatabaseMissing('projects', ['title' => 'Proyek Titipan']);
    }

    private function memberWithProfile(): Member
    {
        $user = User::factory()->create([
            'name' => 'Nadia Putri',
            'must_change_password' => false,
        ]);

        return Member::factory()->create([
            'user_id' => $user->id,
            'name' => $user->name,
        ]);
    }
}
