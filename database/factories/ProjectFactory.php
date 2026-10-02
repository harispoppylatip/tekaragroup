<?php

namespace Database\Factories;

use App\Models\Project;
use App\ProjectCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->catchPhrase();

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'summary' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'categories' => [ProjectCategory::Website],
            'client' => fake()->company(),
            'year' => 2026,
            'tech_stack' => ['Laravel', 'MySQL'],
            'highlights' => [fake()->sentence()],
            'sort_order' => fake()->numberBetween(1, 10),
        ];
    }

    /**
     * Mark the project as an IoT project.
     */
    public function iot(): static
    {
        return $this->state(fn (): array => [
            'categories' => [ProjectCategory::Iot],
            'tech_stack' => ['ESP32', 'PlatformIO'],
        ]);
    }
}
