<?php

namespace App\Http\Requests;

use App\Models\Member;
use App\UserRole;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MemberProfileRequest extends FormRequest
{
    /**
     * Authorization happens in the controller through MemberPolicy.
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
        /** @var Member|null $member */
        $member = $this->route('member');
        $isAdmin = (bool) $this->user()?->isAdmin();
        $isAdminForm = $isAdmin && $this->routeIs('panel.members.*');

        return [
            'name' => ['required', 'string', 'max:100'],
            'role' => ['required', 'string', 'max:100'],
            'headline' => ['required', 'string', 'max:200'],
            'summary' => ['required', 'string', 'max:2000'],
            'location' => ['nullable', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:30'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_photo' => ['boolean'],
            'cv_file' => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
            'remove_cv_file' => ['boolean'],

            'links' => ['array', 'max:10'],
            'links.*.label' => ['nullable', 'string', 'max:40', 'required_with:links.*.url'],
            'links.*.url' => ['nullable', 'url:http,https', 'max:255', 'required_with:links.*.label'],

            'skills' => ['array', 'max:10'],
            'skills.*.group' => ['nullable', 'string', 'max:40', 'required_with:skills.*.items'],
            'skills.*.items' => ['nullable', 'string', 'max:300', 'required_with:skills.*.group'],

            'experiences' => ['array', 'max:20'],
            'experiences.*.title' => ['nullable', 'string', 'max:100', 'required_with:experiences.*.place'],
            'experiences.*.place' => ['nullable', 'string', 'max:150'],
            'experiences.*.period' => ['nullable', 'string', 'max:50'],
            'experiences.*.description' => ['nullable', 'string', 'max:1000'],

            'educations' => ['array', 'max:10'],
            'educations.*.school' => ['nullable', 'string', 'max:150', 'required_with:educations.*.major'],
            'educations.*.major' => ['nullable', 'string', 'max:150'],
            'educations.*.period' => ['nullable', 'string', 'max:50'],

            'certifications' => ['array', 'max:20'],
            'certifications.*.name' => ['nullable', 'string', 'max:150', 'required_with:certifications.*.issuer'],
            'certifications.*.issuer' => ['nullable', 'string', 'max:150'],
            'certifications.*.year' => ['nullable', 'string', 'max:20'],

            'login_email' => $isAdminForm ? [
                Rule::requiredIf(! $member instanceof Member || $member->user_id === null),
                'nullable',
                'email',
                'max:150',
                Rule::unique('users', 'email')->ignore($member?->user_id),
            ] : ['prohibited'],
            'account_role' => $isAdminForm ? ['nullable', Rule::enum(UserRole::class)] : ['prohibited'],
            'sort_order' => $isAdminForm ? ['nullable', 'integer', 'min:0', 'max:999'] : ['prohibited'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'nama',
            'role' => 'peran',
            'headline' => 'kalimat singkat',
            'summary' => 'tentang',
            'login_email' => 'email login',
            'photo' => 'foto',
            'cv_file' => 'file CV',
        ];
    }

    /**
     * Profile columns ready for the members table, with empty rows dropped.
     *
     * @return array<string, mixed>
     */
    public function profile(): array
    {
        $data = $this->safe()->only(['name', 'role', 'headline', 'summary', 'location', 'email', 'phone']);

        if ($this->filled('sort_order')) {
            $data['sort_order'] = $this->integer('sort_order');
        }

        return [
            ...$data,
            'links' => $this->rows('links', ['label', 'url']),
            'skills' => array_map(fn (array $row): array => [
                'group' => $row['group'],
                'items' => array_values(array_filter(array_map('trim', explode(',', $row['items'])))),
            ], $this->rows('skills', ['group', 'items'])),
            'experiences' => $this->rows('experiences', ['title', 'place', 'period', 'description']),
            'educations' => $this->rows('educations', ['school', 'major', 'period']),
            'certifications' => $this->rows('certifications', ['name', 'issuer', 'year']),
        ];
    }

    /**
     * Account role chosen by an admin, if any.
     */
    public function accountRole(): ?UserRole
    {
        return $this->filled('account_role') ? UserRole::from($this->string('account_role')->toString()) : null;
    }

    /**
     * Validated repeater rows with every key present and blank rows removed.
     *
     * @param  array<int, string>  $fields
     * @return array<int, array<string, string>>
     */
    private function rows(string $key, array $fields): array
    {
        return collect($this->validated($key, []))
            ->map(fn (array $row): array => collect($fields)
                ->mapWithKeys(fn (string $field): array => [$field => trim((string) ($row[$field] ?? ''))])
                ->all())
            ->filter(fn (array $row): bool => implode('', $row) !== '')
            ->values()
            ->all();
    }
}
