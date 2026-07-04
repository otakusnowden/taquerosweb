<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Solution extends Model
{
    protected $fillable = [
        'slug', 'name', 'tagline', 'summary', 'description', 'icon',
        'badge', 'status', 'is_flagship', 'sort', 'includes', 'premium', 'features',
    ];

    protected $casts = [
        'is_flagship' => 'boolean',
        'includes' => 'array',
        'premium' => 'array',
        'features' => 'array',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function isAvailable(): bool
    {
        return $this->status === 'active';
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }
}
