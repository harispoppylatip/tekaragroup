<?php

namespace App\Http\Requests;

use App\ProjectCategory;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjectRequest extends FormRequest
{
    /**
     * Authorization happens in the controller through ProjectPolicy.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $isAdmin = (bool) $this->user()?->isAdmin();

        return [
            'title' => ['required', 'string', 'max:150'],
            'summary' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:5000'],
            'categories' => ['required', 'array', 'min:1'],
            'categories.*' => [Rule::enum(ProjectCategory::class)],
            'client' => ['nullable', 'string', 'max:150'],
            'year' => ['required', 'integer', 'min:2000', 'max:'.(now()->year + 1)],
            'tech_stack' => ['nullable', 'string', 'max:500'],
            'highlights' => ['nullable', 'string', 'max:3000'],
            'url' => ['nullable', 'url:http,https', 'max:255'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'remove_cover_image' => ['boolean'],
            'team' => ['array'],
            'team.*.selected' => ['boolean'],
            'team.*.contribution' => ['nullable', 'string', 'max:150'],
            'is_featured' => $isAdmin ? ['boolean'] : ['prohibited'],
            'sort_order' => $isAdmin ? ['nullable', 'integer', 'min:0', 'max:999'] : ['prohibited'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'title' => 'judul',
            'summary' => 'ringkasan',
            'description' => 'deskripsi',
            'categories' => 'kategori',
            'year' => 'tahun',
            'cover_image' => 'gambar sampul',
        ];
    }

    /**
     * Project columns ready for the projects table.
     *
     * @return array<string, mixed>
     */
    public function project(): array
    {
        $data = [
            ...$this->safe()->only(['title', 'summary', 'description', 'client', 'year', 'url']),
            'categories' => array_values(array_unique($this->validated('categories'))),
            'tech_stack' => $this->splitList((string) $this->validated('tech_stack'), '/,/'),
            'highlights' => $this->splitList((string) $this->validated('highlights'), '/\R/'),
        ];

        if ($this->user()?->isAdmin()) {
            $data['is_featured'] = $this->boolean('is_featured');

            if ($this->filled('sort_order')) {
                $data['sort_order'] = $this->integer('sort_order');
            }
        }

        return $data;
    }

    /**
     * Selected team members keyed by member id, with their contribution.
     *
     * @return array<int, array{contribution: string|null}>
     */
    public function team(): array
    {
        return collect($this->validated('team', []))
            ->filter(fn (array $row): bool => (bool) ($row['selected'] ?? false))
            ->mapWithKeys(fn (array $row, int|string $memberId): array => [
                (int) $memberId => ['contribution' => trim((string) ($row['contribution'] ?? '')) ?: null],
            ])
            ->all();
    }

    /**
     * Split free text into trimmed, non-empty items.
     *
     * @return array<int, string>
     */
    private function splitList(string $text, string $pattern): array
    {
        return array_values(array_filter(array_map('trim', preg_split($pattern, $text) ?: [])));
    }
}
