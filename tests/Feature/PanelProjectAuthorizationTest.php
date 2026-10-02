<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\Project;
use App\Models\User;
use App\Services\MemberAccountService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PanelProjectAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_member_can_add_a_project_and_joins_the_team_automatically(): void
    {
        $member = $this->memberWithAccount();

        $this->actingAs($member->user)
            ->post(route('panel.projects.store'), $this->projectPayload())
            ->assertRedirect(route('panel.projects.index'));

        $project = Project::where('title', 'Portal Arsip Digital')->firstOrFail();

        $this->assertSame('portal-arsip-digital', $project->slug);
        $this->assertSame(['Laravel', 'Tailwind CSS'], $project->tech_stack);
        $this->assertFalse($project->is_featured);
        $this->assertTrue($project->members->contains($member));
    }

    public function test_new_project_gets_a_slug_that_is_still_free(): void
    {
        Project::factory()->create(['slug' => 'portal-arsip-digital']);

        $member = $this->memberWithAccount();

        $this->actingAs($member->user)
            ->post(route('panel.projects.store'), $this->projectPayload())
            ->assertRedirect(route('panel.projects.index'));

        $this->assertDatabaseHas('projects', ['slug' => 'portal-arsip-digital-2']);
    }

    public function test_member_cannot_edit_a_project_they_are_not_part_of(): void
    {
        $project = Project::factory()->create();
        $member = $this->memberWithAccount();

        $this->actingAs($member->user)->get(route('panel.projects.edit', $project))->assertForbidden();
        $this->actingAs($member->user)->put(route('panel.projects.update', $project), $this->projectPayload())->assertForbidden();
        $this->actingAs($member->user)->delete(route('panel.projects.destroy', $project))->assertForbidden();
    }

    public function test_member_can_edit_a_project_they_are_part_of(): void
    {
        $project = Project::factory()->create();
        $member = $this->memberWithAccount();
        $project->members()->attach($member);

        $this->actingAs($member->user)->get(route('panel.projects.edit', $project))->assertOk();

        $this->actingAs($member->user)
            ->put(route('panel.projects.update', $project), $this->projectPayload(['title' => 'Portal Arsip Digital v2']))
            ->assertRedirect(route('panel.projects.index'));

        $this->assertSame('Portal Arsip Digital v2', $project->refresh()->title);
    }

    public function test_member_cannot_feature_a_project(): void
    {
        $member = $this->memberWithAccount();

        $this->actingAs($member->user)
            ->post(route('panel.projects.store'), $this->projectPayload(['is_featured' => 1, 'sort_order' => 1]))
            ->assertSessionHasErrors(['is_featured', 'sort_order']);
    }

    public function test_admin_can_feature_and_delete_a_project(): void
    {
        $admin = User::factory()->admin()->create(['must_change_password' => false]);
        $project = Project::factory()->create();

        $this->actingAs($admin)
            ->put(route('panel.projects.update', $project), $this->projectPayload([
                'title' => $project->title,
                'is_featured' => 1,
            ]))
            ->assertRedirect(route('panel.projects.index'));

        $this->assertTrue($project->refresh()->is_featured);

        $this->actingAs($admin)
            ->delete(route('panel.projects.destroy', $project))
            ->assertRedirect(route('panel.projects.index'));

        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }

    public function test_project_needs_at_least_one_category(): void
    {
        $member = $this->memberWithAccount();

        $this->actingAs($member->user)
            ->post(route('panel.projects.store'), $this->projectPayload(['categories' => []]))
            ->assertSessionHasErrors('categories');
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function projectPayload(array $overrides = []): array
    {
        return [
            'title' => 'Portal Arsip Digital',
            'summary' => 'Arsip dokumen yang bisa dicari cepat.',
            'description' => 'Kami membangun portal arsip untuk menata dokumen lama.',
            'categories' => ['website'],
            'year' => 2026,
            'tech_stack' => 'Laravel, Tailwind CSS',
            'highlights' => 'Pencarian cepat',
            'team' => [],
            ...$overrides,
        ];
    }

    private function memberWithAccount(): Member
    {
        $member = app(MemberAccountService::class)->create([
            'name' => 'Rizky Pratama',
            'role' => 'Backend Developer',
            'headline' => 'Merawat layanan yang dipakai setiap hari.',
            'summary' => 'Bekerja pada API, basis data, dan pengujian.',
            'skills' => [['group' => 'Backend', 'items' => ['Laravel', 'MySQL']]],
            'experiences' => [[
                'title' => 'Backend Developer',
                'place' => 'Tekara',
                'period' => '2024 - sekarang',
                'description' => 'Merawat API dan basis data.',
            ]],
            'educations' => [[
                'school' => 'SMK Negeri 1 Samarinda',
                'major' => 'Rekayasa Perangkat Lunak',
                'period' => '2020 - 2023',
            ]],
            'links' => [],
            'certifications' => [],
        ], 'rizky@tekara.my.id');

        $member->user->update(['must_change_password' => false]);

        return $member;
    }
}
