<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class RadioStation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'tagline',
        'description',
        'logo_path',
        'frequency',
        'band',
        'genre',
        'language',
        'country',
        'timezone',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected $appends = ['logo_url'];

    // ─── Relationships ──────────────────────────────────────────────────────────

    public function streams(): HasMany
    {
        return $this->hasMany(StreamConfig::class);
    }

    public function activeStreams(): HasMany
    {
        return $this->hasMany(StreamConfig::class)->where('is_active', true);
    }

    public function defaultStream()
    {
        return $this->hasOne(StreamConfig::class)->where('is_default', true)->where('is_active', true);
    }

    // ─── Accessors ───────────────────────────────────────────────────────────────

    public function getLogoUrlAttribute(): ?string
    {
        if ($this->logo_path) {
            return asset('storage/' . $this->logo_path);
        }
        return null;
    }

    // ─── Scopes ──────────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    public function scopeByBand($query, string $band)
    {
        return $query->where('band', strtoupper($band));
    }
}
