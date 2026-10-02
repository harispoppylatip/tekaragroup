<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PanelPostPublishingTest extends TestCase
{
    use RefreshDatabase;

    public function test_draft_posts_stay_hidden_from_visitors(): void
    {
        $draft = Post::factory()->create(['title' => 'Catatan Internal', 'published_at' => null]);

        $this->get(route('posts.index'))->assertOk()->assertDontSee('Catatan Internal');
        $this->get(route('posts.show', $draft))->assertNotFound();
    }

    public function test_future_posts_are_not_visible_yet(): void
    {
        $planned = Post::factory()->create([
            'title' => 'Rencana Tahun Depan',
            'published_at' => now()->addWeek(),
        ]);

        $this->get(route('posts.index'))->assertOk()->assertDontSee('Rencana Tahun Depan');
        $this->get(route('posts.show', $planned))->assertNotFound();
    }

    public function test_published_post_is_readable_and_renders_markdown(): void
    {
        $post = Post::factory()->create([
            'title' => 'AbsenMu Sudah Rilis',
            'excerpt' => 'Kabar singkat dari tim.',
            'body' => "Portal presensi kami sudah dipakai.\n\nKami menulis **catatan rilis** untuk sekolah.",
            'published_at' => now()->subDay(),
        ]);

        $this->get(route('posts.index'))->assertOk()->assertSee('AbsenMu Sudah Rilis');

        $this->get(route('posts.show', $post))
            ->assertOk()
            ->assertSee('AbsenMu Sudah Rilis')
            ->assertSee('<strong>catatan rilis</strong>', false);
    }

    public function test_published_posts_appear_on_the_home_page(): void
    {
        Post::factory()->create(['title' => 'Kabar Terbaru Kami', 'published_at' => now()->subDay()]);

        $this->get(route('home'))->assertOk()->assertSee('Kabar Terbaru Kami');
    }

    public function test_unknown_news_slug_returns_not_found(): void
    {
        $this->get('/berita/berita-yang-tidak-ada')->assertNotFound();
    }

    public function test_author_can_publish_a_post_from_the_panel(): void
    {
        $author = $this->panelUser();

        $this->actingAs($author)
            ->post(route('panel.posts.store'), $this->postPayload(['publish' => 1]))
            ->assertRedirect(route('panel.posts.index'));

        $post = Post::where('slug', 'catatan-rilis-pertama')->firstOrFail();

        $this->assertSame($author->id, $post->user_id);
        $this->assertTrue($post->isPublished());
    }

    public function test_author_can_save_a_post_as_a_draft(): void
    {
        $author = $this->panelUser();

        $this->actingAs($author)
            ->from(route('panel.posts.create'))
            ->followingRedirects()
            ->post(route('panel.posts.store'), $this->postPayload())
            ->assertSee('disimpan sebagai draf');

        $post = Post::where('slug', 'catatan-rilis-pertama')->firstOrFail();

        $this->assertFalse($post->isPublished());
        $this->assertNull($post->published_at);
    }

    public function test_member_can_edit_their_own_post_but_not_somebody_elses(): void
    {
        $author = $this->panelUser();
        $other = $this->panelUser('rekan@tekara.my.id');
        $post = Post::factory()->create(['user_id' => $author->id]);

        $this->actingAs($author)->get(route('panel.posts.edit', $post))->assertOk();
        $this->actingAs($other)->get(route('panel.posts.edit', $post))->assertForbidden();
        $this->actingAs($other)->delete(route('panel.posts.destroy', $post))->assertForbidden();
    }

    public function test_post_needs_a_title_excerpt_and_body(): void
    {
        $author = $this->panelUser();

        $this->actingAs($author)
            ->post(route('panel.posts.store'), ['title' => '', 'excerpt' => '', 'body' => ''])
            ->assertSessionHasErrors(['title', 'excerpt', 'body']);
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function postPayload(array $overrides = []): array
    {
        return [
            'title' => 'Catatan Rilis Pertama',
            'excerpt' => 'Ringkasan singkat tentang rilis pertama kami.',
            'body' => 'Isi berita yang cukup panjang untuk dibaca.',
            ...$overrides,
        ];
    }

    private function panelUser(string $email = 'penulis@tekara.my.id'): User
    {
        return User::factory()->create([
            'name' => 'Penulis Tekara',
            'email' => $email,
            'must_change_password' => false,
        ]);
    }
}
