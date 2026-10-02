<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\User;
use App\Services\MemberAccountService;
use App\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PanelMemberManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_add_a_member_together_with_a_login_account(): void
    {
        $admin = User::factory()->admin()->create(['must_change_password' => false]);

        $this->actingAs($admin)
            ->from(route('panel.members.create'))
            ->followingRedirects()
            ->post(route('panel.members.store'), $this->profile([
                'login_email' => 'nadia@tekara.my.id',
                'account_role' => 'member',
            ]))
            ->assertSee('Akun masuk: nadia@tekara.my.id');

        $member = Member::where('name', 'Nadia Putri')->firstOrFail();
        $user = User::where('email', 'nadia@tekara.my.id')->firstOrFail();

        $this->assertNotSame($admin->id, $user->id);
        $this->assertSame($user->id, $member->user_id);
        $this->assertSame(UserRole::Member, $user->role);
        $this->assertTrue($user->must_change_password);
        $this->assertTrue(Hash::check(config('tekara.default_password'), $user->password));
    }

    public function test_new_member_needs_an_email_that_is_not_used_yet(): void
    {
        $admin = User::factory()->admin()->create(['must_change_password' => false]);

        $this->actingAs($admin)
            ->post(route('panel.members.store'), $this->profile([
                'login_email' => $admin->email,
                'account_role' => 'member',
            ]))
            ->assertSessionHasErrors('login_email');

        $this->assertDatabaseMissing('members', ['name' => 'Nadia Putri']);
    }

    public function test_new_member_needs_a_login_email(): void
    {
        $admin = User::factory()->admin()->create(['must_change_password' => false]);

        $this->actingAs($admin)
            ->post(route('panel.members.store'), $this->profile())
            ->assertSessionHasErrors('login_email');
    }

    public function test_admin_cannot_downgrade_their_own_account(): void
    {
        $admin = User::factory()->admin()->create(['must_change_password' => false]);
        $member = Member::factory()->create(['user_id' => $admin->id, 'name' => $admin->name]);

        $this->actingAs($admin)
            ->put(route('panel.members.update', $member), $this->profile([
                'name' => $admin->name,
                'login_email' => $admin->email,
                'account_role' => UserRole::Member->value,
            ]))
            ->assertRedirect(route('panel.members.index'));

        $this->assertSame(UserRole::Admin, $admin->refresh()->role);
    }

    public function test_admin_can_change_another_account_role(): void
    {
        $admin = User::factory()->admin()->create(['must_change_password' => false]);
        $member = $this->memberWithAccount('Rizky Pratama', 'rizky@tekara.my.id');

        $this->actingAs($admin)
            ->put(route('panel.members.update', $member), $this->profile([
                'name' => 'Rizky Pratama',
                'login_email' => 'rizky@tekara.my.id',
                'account_role' => UserRole::Admin->value,
            ]))
            ->assertRedirect(route('panel.members.index'));

        $this->assertSame(UserRole::Admin, $member->user->refresh()->role);
    }

    public function test_admin_can_send_a_member_back_to_the_default_password(): void
    {
        $admin = User::factory()->admin()->create(['must_change_password' => false]);
        $member = $this->memberWithAccount('Dimas Saputra', 'dimas@tekara.my.id');

        $member->user->update(['password' => 'SandiSendiri2026!', 'must_change_password' => false]);

        $this->actingAs($admin)
            ->post(route('panel.members.reset-password', $member))
            ->assertRedirect();

        $user = $member->user->refresh();

        $this->assertTrue($user->must_change_password);
        $this->assertTrue(Hash::check(config('tekara.default_password'), $user->password));
    }

    public function test_member_cannot_manage_members_or_settings(): void
    {
        $member = $this->memberWithAccount('Dimas Saputra', 'dimas@tekara.my.id');
        $other = Member::factory()->create();

        $this->actingAs($member->user)
            ->post(route('panel.members.store'), $this->profile())
            ->assertForbidden();

        $this->actingAs($member->user)
            ->post(route('panel.members.reset-password', $other))
            ->assertForbidden();

        $this->actingAs($member->user)
            ->delete(route('panel.members.destroy', $other))
            ->assertForbidden();
    }

    public function test_member_cannot_promote_themselves_from_the_cv_form(): void
    {
        $member = $this->memberWithAccount('Nadia Putri', 'nadia@tekara.my.id');

        $this->actingAs($member->user)
            ->put(route('panel.cv.update'), $this->profile([
                'name' => 'Nadia Putri',
                'account_role' => UserRole::Admin->value,
                'sort_order' => 1,
            ]))
            ->assertSessionHasErrors(['account_role', 'sort_order']);

        $this->assertSame(UserRole::Member, $member->user->refresh()->role);
    }

    public function test_admin_can_delete_a_member_and_their_account(): void
    {
        $admin = User::factory()->admin()->create(['must_change_password' => false]);
        $member = $this->memberWithAccount('Dimas Saputra', 'dimas@tekara.my.id');
        $userId = $member->user_id;

        $this->actingAs($admin)
            ->delete(route('panel.members.destroy', $member))
            ->assertRedirect(route('panel.members.index'));

        $this->assertDatabaseMissing('members', ['id' => $member->id]);
        $this->assertDatabaseMissing('users', ['id' => $userId]);
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function profile(array $overrides = []): array
    {
        return [
            'name' => 'Nadia Putri',
            'role' => 'UI/UX dan Frontend Developer',
            'headline' => 'Merancang antarmuka yang tenang dan mudah dipakai.',
            'summary' => 'Menangani riset kecil, rancangan, sampai potongan antarmuka.',
            'location' => 'Samarinda',
            'email' => 'nadia@contoh.test',
            ...$overrides,
        ];
    }

    /**
     * Profile array for MemberAccountService, which writes straight to the table.
     *
     * @return array<string, mixed>
     */
    private function serviceProfile(string $name): array
    {
        return [
            'name' => $name,
            'role' => 'UI/UX dan Frontend Developer',
            'headline' => 'Merancang antarmuka yang tenang dan mudah dipakai.',
            'summary' => 'Menangani riset kecil, rancangan, sampai potongan antarmuka.',
            'skills' => [['group' => 'Web', 'items' => ['Laravel', 'Tailwind CSS']]],
            'experiences' => [[
                'title' => 'Frontend Developer',
                'place' => 'Tekara',
                'period' => '2024 - sekarang',
                'description' => 'Merapikan antarmuka panel.',
            ]],
            'educations' => [[
                'school' => 'SMK Negeri 1 Samarinda',
                'major' => 'Rekayasa Perangkat Lunak',
                'period' => '2020 - 2023',
            ]],
            'links' => [],
            'certifications' => [],
        ];
    }

    private function memberWithAccount(string $name, string $email): Member
    {
        $member = app(MemberAccountService::class)->create($this->serviceProfile($name), $email);
        $member->user->update(['must_change_password' => false]);

        return $member;
    }
}
