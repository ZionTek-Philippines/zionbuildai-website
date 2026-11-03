<?php

namespace App\Models;

use Database\Factories\DemoLeadFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * A visitor who filled in the "Unlock the full demo" form on the marketing site.
 */
#[Fillable(['name', 'mobile', 'company', 'role', 'unlocked_from'])]
class DemoLead extends Model
{
    /** @use HasFactory<DemoLeadFactory> */
    use HasFactory;

    /**
     * Role options shown on the unlock form, keyed by stored value.
     *
     * @var array<string, string>
     */
    public const ROLES = [
        'contractor' => 'Contractor / Business owner',
        'engineer' => 'Engineer / Project manager',
        'homeowner' => 'Homeowner',
        'other' => 'Other',
    ];
}
