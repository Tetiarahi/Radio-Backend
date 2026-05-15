<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'label',
        'description',
    ];

    // ─── Helper: get cast value ───────────────────────────────────────────────

    public function getTypedValueAttribute(): mixed
    {
        return match ($this->type) {
            'boolean' => filter_var($this->value, FILTER_VALIDATE_BOOLEAN),
            'integer' => (int) $this->value,
            'json'    => json_decode($this->value, true),
            default   => $this->value,
        };
    }

    // ─── Static helper: get single setting value ──────────────────────────────

    public static function get(string $key, mixed $default = null): mixed
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->typed_value : $default;
    }

    // ─── Static helper: get all settings as key=>value array ─────────────────

    public static function allAsArray(): array
    {
        return static::all()->mapWithKeys(function (AppSetting $s) {
            return [$s->key => $s->typed_value];
        })->toArray();
    }

    // ─── Static helper: set a value ───────────────────────────────────────────

    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
    }
}
