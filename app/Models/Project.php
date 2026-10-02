<?php

namespace App\Models;

use App\ProjectCategory;
use Database\Factories\ProjectFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\AsEnumCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

/**
 * @property Collection<int, ProjectCategory> $categories
 * @property array<int, string> $tech_stack
 * @property array<int, string> $highlights
 */
#[Fillable([
    'title', 'slug', 'summary', 'description', 'categories', 'client', 'year',
    'tech_stack', 'highlights', 'url', 'cover_image', 'is_featured', 'sort_order',
])]
class Project extends Model
{
    /** @use HasFactory<ProjectFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, mixed>
     */
    protected function casts(): array
    {
        return [
            'categories' => AsEnumCollection::of(ProjectCategory::class),
            'tech_stack' => 'array',
            'highlights' => 'array',
            'is_featured' => 'boolean',
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
     * @return BelongsToMany<Member, $this>
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(Member::class)
            ->withPivot('contribution')
            ->orderBy('sort_order');
    }

    /**
     * Category values joined by spaces, used by the client-side filter.
     */
    public function categoryKeys(): string
    {
        return $this->categories->map(fn (ProjectCategory $category): string => $category->value)->implode(' ');
    }

    /**
     * Whether the project combines both specialties.
     */
    public function isWebAndIot(): bool
    {
        return $this->categories->contains(ProjectCategory::Website)
            && $this->categories->contains(ProjectCategory::Iot);
    }
}
