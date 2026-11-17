<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Workshop extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'speaker',
        'location',
        'starts_at',
        'price',
        'quota',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'price' => 'integer',
            'quota' => 'integer',
        ];
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    public function paidRegistrations(): HasMany
    {
        return $this->hasMany(Registration::class)->where('status', 'paid');
    }

    public function getRemainingQuotaAttribute(): int
    {
        // If paid_registrations_count was eager loaded via withCount
        if (isset($this->attributes['paid_registrations_count'])) {
            return max(0, $this->quota - (int) $this->attributes['paid_registrations_count']);
        }

        return max(0, $this->quota - $this->paidRegistrations()->count());
    }

    public function isFull(): bool
    {
        return $this->remaining_quota <= 0;
    }
}