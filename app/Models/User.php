<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'twitch_id',
        'twitch_token',
        'twitch_refresh_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'twitch_token',
        'twitch_refresh_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function case_openings(): HasMany
    {
        return $this->hasMany(CaseOpening::class);
    }

    public function case_opening_redemptions(): HasMany
    {
        return $this->hasMany(CaseOpeningRedemption::class);
    }
    public function getFilteredRedemptionsAttribute(): Collection
    {
        return $this->case_opening_redemptions->filter(function ($redemption) {
            return $redemption->twitch_user_id !== $this->twitch_id;
        });
    }

    public function getOpenedCasesAttribute(): int
    {
        return $this->filtered_redemptions->count();
    }

    public function getPointsSpentAttribute(): int
    {
        return $this->filtered_redemptions->sum('twitch_reward_cost');
    }
}
