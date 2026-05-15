<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StreamConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'radio_station_id',
        'label',
        'stream_url',
        'stream_type',
        'codec',
        'bitrate',
        'is_https',
        'is_default',
        'is_active',
        'metadata_url',
    ];

    protected $casts = [
        'is_https' => 'boolean',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
        'bitrate' => 'integer',
    ];

    // ─── Relationships ──────────────────────────────────────────────────────────

    public function station(): BelongsTo
    {
        return $this->belongsTo(RadioStation::class, 'radio_station_id');
    }

    // ─── Scopes ──────────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }
}
