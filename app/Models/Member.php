<?php

namespace App\Models;

use Database\Factories\MemberFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

/**
 * @property array<int, array{group: string, items: array<int, string>}> $skills
 * @property array<int, array{title: string, place: string, period: string, description: string}> $experiences
 * @property array<int, array{school: string, major: string, period: string}> $educations
 * @property array<int, array{name: string, issuer: string, year: string}>|null $certifications
 * @property array<int, array{label: string, url: string}>|null $links
 */
#[Fillable([
    'user_id', 'name', 'slug', 'role', 'headline', 'summary', 'location', 'email', 'phone',
    'photo', 'cv_file', 'links', 'skills', 'experiences', 'educations', 'certifications', 'sort_order',
])]
class Member extends Model
{
    /** @use HasFactory<MemberFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'links' => 'array',
            'skills' => 'array',
            'experiences' => 'array',
            'educations' => 'array',
            'certifications' => 'array',
        ];
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * The login account synced with this member.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsToMany<Project, $this>
     */
    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class)
            ->withPivot('contribution')
            ->orderBy('sort_order');
    }

    /**
     * Two-letter initials used when the member has no photo.
     */
    protected function initials(): Attribute
    {
        return Attribute::get(fn (): string => Str::of($this->name)
            ->explode(' ')
            ->filter()
            ->take(2)
            ->map(fn (string $word): string => Str::upper(Str::substr($word, 0, 1)))
            ->implode(''));
    }

    /**
     * The most relevant skills shown on the team card.
     *
     * @return array<int, string>
     */
    public function topSkills(int $limit = 4): array
    {
        return collect($this->skills)
            ->pluck('items')
            ->flatten()
            ->take($limit)
            ->all();
    }
}
