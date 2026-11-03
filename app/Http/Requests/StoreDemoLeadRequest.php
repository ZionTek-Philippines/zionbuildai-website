<?php

namespace App\Http\Requests;

use App\Models\DemoLead;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDemoLeadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Accept mobile numbers typed with spaces or dashes (e.g. "0917 123 4567").
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'mobile' => preg_replace('/[\s-]/', '', (string) $this->input('mobile')),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * `website` is a honeypot: hidden from people, filled in by bots.
     *
     * @return array<string, array<mixed>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'mobile' => ['required', 'regex:/^(\+63|0)9\d{9}$/'],
            'company' => ['required', 'string', 'max:150'],
            'role' => ['required', Rule::in(array_keys(DemoLead::ROLES))],
            'unlocked_from' => ['nullable', 'string', 'max:60'],
            'website' => ['prohibited'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'mobile.regex' => 'Please enter a Philippine mobile number, e.g. 0917 123 4567.',
        ];
    }
}
