<?php

namespace Database\Factories;

use App\Models\Member;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Member>
 */
class MemberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->name();

        return [
            'name' => $name,
            'slug' => Str::slug($name).'-'.fake()->unique()->numberBetween(1, 9999),
            'role' => fake()->jobTitle(),
            'headline' => fake()->sentence(),
            'summary' => fake()->paragraph(),
            'location' => fake()->city(),
            'email' => fake()->safeEmail(),
            'skills' => [
                ['group' => 'Web', 'items' => ['Laravel', 'Tailwind CSS']],
                ['group' => 'IoT', 'items' => ['ESP32']],
            ],
            'experiences' => [
                [
                    'title' => fake()->jobTitle(),
                    'place' => fake()->company(),
                    'period' => '2025 - sekarang',
                    'description' => fake()->sentence(),
                ],
            ],
            'educations' => [
                ['school' => fake()->company(), 'major' => 'Informatika', 'period' => '2022 - sekarang'],
            ],
            'sort_order' => fake()->numberBetween(1, 10),
        ];
    }
}
