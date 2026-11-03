<?php

namespace App\Models;

use Database\Factories\SiteEventFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Something a visitor did during a visit: viewed a page, clicked, read a section, scrolled or started a form.
 *
 * `value` holds seconds for `section_time` and a percentage (25/50/75/100) for `scroll`.
 * `form_field` records which field was focused (its label, never what was typed), to find where people give up on a form.
 */
#[Fillable(['site_visit_id', 'type', 'path', 'section', 'label', 'value', 'created_at'])]
class SiteEvent extends Model
{
    /** @use HasFactory<SiteEventFactory> */
    use HasFactory;

    public const UPDATED_AT = null;

    /**
     * @var list<string>
     */
    public const TYPES = ['page_view', 'click', 'section_time', 'scroll', 'form_start', 'form_field', 'form_submit'];

    /**
     * Friendly names for site paths, shared by the analytics cards and the visit timeline.
     *
     * @var array<string, string>
     */
    public const PAGE_NAMES = [
        '/' => 'Homepage',
        '/about' => 'About',
        '/contact' => 'Contact',
        '/privacy-policy' => 'Privacy Policy',
        '/terms-of-service' => 'Terms of Service',
        '/data-security' => 'Data Security',
        '/demo' => 'Live demo · Command Center',
    ];

    public static function pageName(string $path): string
    {
        return match (true) {
            isset(self::PAGE_NAMES[$path]) => self::PAGE_NAMES[$path],
            str_starts_with($path, '/demo/') => 'Live demo · '.str($path)->after('/demo/')->headline()->replace('Ai ', 'AI '),
            default => $path,
        };
    }

    /**
     * One plain-English line for the visit timeline, e.g. "Read Features for 1m 20s".
     */
    public function description(): string
    {
        $seconds = (int) $this->value;

        return match ($this->type) {
            'page_view' => 'Viewed '.self::pageName($this->path),
            'click' => 'Clicked "'.$this->label.'"'.($this->section ? " in {$this->section}" : ' on '.self::pageName($this->path)),
            'section_time' => "Read {$this->section} for ".($seconds < 60 ? "{$seconds}s" : intdiv($seconds, 60).'m '.($seconds % 60).'s'),
            'scroll' => "Scrolled {$this->value}% down the ".self::pageName($this->path),
            'form_start' => "Started the {$this->label} form",
            'form_field' => "Clicked into the \"{$this->label}\" field",
            'form_submit' => "Submitted the {$this->label} form",
            default => $this->type,
        };
    }

    /**
     * @return BelongsTo<SiteVisit, $this>
     */
    public function visit(): BelongsTo
    {
        return $this->belongsTo(SiteVisit::class, 'site_visit_id');
    }
}
