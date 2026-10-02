<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiteSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_falls_back_to_the_default_wording(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee(config('tekara.settings.hero_title'))
            ->assertSee(config('tekara.settings.contact_title'));
    }

    public function test_admin_can_save_the_website_settings(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)
            ->put(route('panel.settings.update'), $this->payload())
            ->assertRedirect(route('panel.settings.edit'));

        $this->assertDatabaseHas('settings', ['key' => 'hero_title', 'value' => 'Studio kecil dengan hasil nyata.']);
    }

    public function test_saved_settings_show_up_on_the_home_page(): void
    {
        $this->actingAs($this->admin())
            ->put(route('panel.settings.update'), $this->payload());

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Studio kecil dengan hasil nyata.')
            ->assertSee('Rakit, uji, rilis')
            ->assertSee('Mari berbicara')
            ->assertSee('halo@tekara.my.id');
    }

    public function test_settings_form_shows_the_saved_values(): void
    {
        Setting::saveMany(['site_tagline' => 'Rakit, uji, rilis']);

        $this->actingAs($this->admin())
            ->get(route('panel.settings.edit'))
            ->assertOk()
            ->assertSee('Rakit, uji, rilis');
    }

    public function test_empty_value_falls_back_to_the_default_wording(): void
    {
        Setting::saveMany([
            'hero_title' => config('tekara.settings.hero_title'),
            'contact_title' => '',
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee(config('tekara.settings.contact_title'));
    }

    public function test_whatsapp_number_must_start_with_the_country_code(): void
    {
        $this->actingAs($this->admin())
            ->from(route('panel.settings.edit'))
            ->followingRedirects()
            ->put(route('panel.settings.update'), $this->payload(['contact_whatsapp' => '081234567890']))
            ->assertSee('Nomor WhatsApp ditulis dengan awalan 62');
    }

    public function test_instagram_handle_cannot_be_a_link_or_contain_the_at_sign(): void
    {
        $this->actingAs($this->admin())
            ->put(route('panel.settings.update'), $this->payload(['contact_instagram' => '@tekara.id']))
            ->assertSessionHasErrors('contact_instagram');
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function payload(array $overrides = []): array
    {
        return [
            'site_tagline' => 'Rakit, uji, rilis',
            'hero_title' => 'Studio kecil dengan hasil nyata.',
            'hero_text' => 'Kami mengerjakan website dan alat IoT sampai selesai.',
            'meta_description' => 'Tekara mengerjakan website dan alat IoT untuk sekolah dan usaha kecil.',
            'footer_text' => 'Tekara, studio teknologi dari Samarinda.',
            'contact_title' => 'Mari berbicara',
            'contact_text' => 'Ceritakan kebutuhanmu, kami bantu rancang jalan keluarnya.',
            'contact_email' => 'halo@tekara.my.id',
            'contact_whatsapp' => '6281234567890',
            'contact_instagram' => 'tekara.id',
            'contact_location' => 'Samarinda, Kalimantan Timur',
            ...$overrides,
        ];
    }

    private function admin(): User
    {
        return User::factory()->admin()->create(['must_change_password' => false]);
    }
}
