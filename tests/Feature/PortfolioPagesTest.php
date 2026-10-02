<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\Project;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PortfolioPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_lists_projects_and_links_every_member_card_to_their_cv(): void
    {
        $this->seed(DatabaseSeeder::class);

        $response = $this->get(route('home'));

        $response->assertOk();
        $this->assertSame(4, Member::count());

        foreach (Member::all() as $member) {
            $response->assertSee($member->name);
            $response->assertSee(route('members.show', $member), false);
        }

        foreach (Project::all() as $project) {
            $response->assertSee($project->title);
            $response->assertSee('data-categories="'.$project->categoryKeys().'"', false);
        }
    }

    public function test_member_cv_page_shows_experience_skills_and_projects(): void
    {
        $member = Member::factory()->create();
        $project = Project::factory()->create();
        $member->projects()->attach($project, ['contribution' => 'Membangun API']);

        $response = $this->get(route('members.show', $member));

        $response->assertOk();
        $response->assertSee($member->name);
        $response->assertSee($member->experiences[0]['title']);
        $response->assertSee('Laravel');
        $response->assertSee($project->title);
        $response->assertSee('Membangun API');
    }

    public function test_member_download_button_only_appears_when_a_cv_file_exists(): void
    {
        $withoutFile = Member::factory()->create(['cv_file' => null]);
        $withFile = Member::factory()->create(['cv_file' => 'cv/contoh.pdf']);

        $this->get(route('members.show', $withoutFile))->assertDontSee('Unduh CV');
        $this->get(route('members.show', $withFile))
            ->assertSee('Unduh CV')
            ->assertSee('storage/cv/contoh.pdf');
    }

    public function test_project_page_shows_details_and_team(): void
    {
        $project = Project::factory()->iot()->create();
        $member = Member::factory()->create();
        $project->members()->attach($member, ['contribution' => 'Firmware']);

        $this->get(route('projects.show', $project))
            ->assertOk()
            ->assertSee($project->title)
            ->assertSee('ESP32')
            ->assertSee($member->name)
            ->assertSee('Firmware');
    }

    public function test_unknown_member_and_project_return_not_found(): void
    {
        $this->get('/tim/tidak-ada')->assertNotFound();
        $this->get('/proyek/tidak-ada')->assertNotFound();
    }
}
