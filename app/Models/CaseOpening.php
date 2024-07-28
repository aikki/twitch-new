<?php

namespace App\Models;

use App\Exceptions\SelectRewardException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CaseOpening extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'streamerbot_reward_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function rewards(): HasMany
    {
        return $this->hasMany(CaseOpeningReward::class)->orderBy('is_active', 'DESC')->orderBy('weight');
    }

    public function redemptions(): HasMany
    {
        return $this->hasMany(CaseOpeningRedemption::class);
    }

    public function getActiveRewardsAttribute(): Collection
    {
        return $this->rewards->filter(function ($reward) {
                return $reward->is_active;
        });
    }

    public function sumWeights(): int
    {
        return $this->active_rewards->sum('weight');
    }

    public function getRandomRewardWeightedAttribute(): CaseOpeningReward
    {
        if ($this->active_rewards->isEmpty() || $this->sumWeights() < 1)
            throw new SelectRewardException('Opening has no active or properly weighted rewards.');;
        $random = mt_rand(1, $this->sumWeights());
        foreach ($this->active_rewards as $reward) {
            $random -= $reward->weight;
            if ($random <= 0) {
                return $reward;
            }
        }
        throw new SelectRewardException('Cannot randomly select any reward.');
    }

    public function getRandomRewardAttribute(): CaseOpeningReward
    {
        return $this->rewards->random();
    }
}
