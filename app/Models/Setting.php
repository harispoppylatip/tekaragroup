<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Once;

#[Fillable(['key', 'value'])]
class Setting extends Model
{
    private const CACHE_KEY = 'site-settings';

    /**
     * @var string
     */
    protected $primaryKey = 'key';

    /**
     * @var string
     */
    protected $keyType = 'string';

    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * Read one website setting, falling back to the default in config/tekara.php.
     */
    public static function get(string $key): ?string
    {
        return static::allValues()[$key] ?? null;
    }

    /**
     * All settings merged over their defaults.
     *
     * @return array<string, string|null>
     */
    public static function allValues(): array
    {
        $stored = once(fn (): array => Cache::rememberForever(self::CACHE_KEY, fn (): array => static::query()->pluck('value', 'key')->all()));

        return array_merge(config('tekara.settings'), array_filter($stored, fn (?string $value): bool => $value !== null && $value !== ''));
    }

    /**
     * Save several settings at once and refresh the cache.
     *
     * @param  array<string, string|null>  $values
     */
    public static function saveMany(array $values): void
    {
        foreach ($values as $key => $value) {
            static::query()->updateOrCreate(['key' => $key], ['value' => $value]);
        }

        Cache::forget(self::CACHE_KEY);
        Once::flush();
    }
}
