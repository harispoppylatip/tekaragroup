<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AccountProfileRequest extends FormRequest
{
    /**
     * Every signed-in account may edit its own profile.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'email' => [
                'required',
                'string',
                'email',
                'max:150',
                Rule::unique('users', 'email')->ignore($this->user()?->getKey()),
            ],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_photo' => ['boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'nama',
            'email' => 'surel',
            'photo' => 'foto',
        ];
    }

    /**
     * Account columns ready for the users table.
     *
     * @return array<string, string>
     */
    public function profile(): array
    {
        return [
            'name' => trim($this->string('name')->toString()),
            'email' => strtolower(trim($this->string('email')->toString())),
        ];
    }
}
