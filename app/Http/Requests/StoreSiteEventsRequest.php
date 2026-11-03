<?php

namespace App\Http\Requests;

use App\Models\SiteEvent;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * A batch of anonymous analytics events sent by resources/js/site-analytics.js.
 */
class StoreSiteEventsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<mixed>>
     */
    public function rules(): array
    {
        return [
            'visitor_id' => ['required', 'uuid'],
            'visit_id' => ['required', 'uuid'],
            'referrer_host' => ['nullable', 'string', 'max:255'],
            'utm_source' => ['nullable', 'string', 'max:100'],
            'utm_medium' => ['nullable', 'string', 'max:100'],
            'utm_campaign' => ['nullable', 'string', 'max:100'],
            'timezone' => ['nullable', 'string', 'max:50'],
            'events' => ['required', 'array', 'min:1', 'max:50'],
            'events.*.type' => ['required', Rule::in(SiteEvent::TYPES)],
            'events.*.path' => ['required', 'string', 'max:255'],
            'events.*.section' => ['nullable', 'string', 'max:60'],
            'events.*.label' => ['nullable', 'string', 'max:150'],
            'events.*.value' => ['nullable', 'integer', 'min:0', 'max:86400'],
        ];
    }
}
