<?php

namespace App\Models;

use Database\Factories\PreviewPasswordFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * A password for the private homepage, given to one prospective client.
 *
 * Stored encrypted (not hashed) so the admin can read it back and share it.
 * Turning it off, or letting it expire, locks that client out on their next page load.
 */
#[Fillable(['label', 'password', 'is_active', 'expires_at'])]
class PreviewPassword extends Model
{
    /** @use HasFactory<PreviewPasswordFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'encrypted',
            'is_active' => 'boolean',
            'expires_at' => 'datetime',
            'last_used_at' => 'datetime',
        ];
    }

    /**
     * Passwords that are switched on and not expired.
     *
     * @param  Builder<PreviewPassword>  $query
     */
    public function scopeUsable(Builder $query): void
    {
        $query->where('is_active', true)
            ->where(fn (Builder $query) => $query->whereNull('expires_at')->orWhere('expires_at', '>', now()));
    }

    /**
     * Find the usable password matching what a visitor typed. Passwords are encrypted, so each one is compared in PHP.
     */
    public static function findUsableMatching(string $input): ?self
    {
        return self::query()->usable()->get()
            ->first(fn (self $previewPassword): bool => hash_equals($previewPassword->password, $input));
    }

    /**
     * Whether another record already uses this exact password.
     */
    public static function isTaken(string $password, ?int $ignoreId = null): bool
    {
        return self::query()->whereKeyNot($ignoreId)->get()
            ->contains(fn (self $previewPassword): bool => $previewPassword->password === $password);
    }

    /**
     * A random password that is easy to read out or type: no 0/O or 1/l/I.
     */
    public static function generate(): string
    {
        $characters = 'ABCDEFGHJKMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz23456789';

        return collect(range(1, 10))->map(fn (): string => $characters[random_int(0, strlen($characters) - 1)])->implode('');
    }

    public function isUsable(): bool
    {
        return $this->is_active && ($this->expires_at === null || $this->expires_at->isFuture());
    }

    public function recordUse(): void
    {
        $this->forceFill(['last_used_at' => now(), 'uses' => $this->uses + 1])->save();
    }
}
