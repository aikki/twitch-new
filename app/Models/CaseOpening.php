<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CaseOpening extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'streamerbot_reward_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function rewards(): HasMany
    {
        return $this->hasMany(CaseOpeningReward::class)->orderBy('weight');
    }

    public function redemptions(): HasMany
    {
        return $this->hasMany(CaseOpeningRedemption::class);
    }

    public function sumWeights(): int
    {
        return $this->rewards->sum('weight');
    }

    public function getRandomRewardWeightedAttribute(): CaseOpeningReward
    {
        $random = mt_rand(1, $this->sumWeights());
        foreach ($this->rewards as $reward) {
            $random -= $reward->weight;
            if ($random <= 0) {
                return $reward;
            }
        }
        throw new \Exception('Cannot randomly select any reward.');
    }

    public function getRandomRewardAttribute(): CaseOpeningReward
    {
        return $this->rewards->random();
    }
}
