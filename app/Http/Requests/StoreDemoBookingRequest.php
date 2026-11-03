<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDemoBookingRequest extends FormRequest
{
    /**
     * The form lives inside the homepage's #demo section, so errors scroll back to it.
     */
    protected $redirect = '/#demo';

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Accept numbers typed with spaces, dashes or brackets (e.g. "0917 123 4567").
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'contact_number' => preg_replace('/[\s\-().]/', '', (string) $this->input('contact_number')) ?: null,
            'email' => trim((string) $this->input('email')) ?: null,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * At least one of contact number or email is required. `website` is a honeypot: hidden from people, filled in by bots.
     * The `analytics_*` IDs are filled in by resources/js/site-analytics.js when tracking is allowed.
     *
     * @return array<string, array<mixed>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'contact_number' => ['nullable', 'required_without:email', 'regex:/^\+?\d{7,15}$/'],
            'email' => ['nullable', 'required_without:contact_number', 'email', 'max:150'],
            'company_info' => ['required', 'string', 'max:1000'],
            'analytics_visitor_id' => ['nullable', 'uuid'],
            'analytics_visit_id' => ['nullable', 'uuid'],
            'website' => ['prohibited'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'contact_number.required_without' => 'Please give us a contact number or an email so we can reach you.',
            'email.required_without' => 'Please give us a contact number or an email so we can reach you.',
            'contact_number.regex' => 'Please enter a valid phone number, e.g. 0917 123 4567.',
            'company_info.required' => 'Tell us a little about your company or projects.',
        ];
    }
}
