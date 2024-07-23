<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CaseOpeningRedemption extends Model
{
    use HasFactory;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function case_opening(): BelongsTo
    {
        return $this->belongsTo(CaseOpening::class);
    }

    public function reward(): BelongsTo
    {
        return $this->belongsTo(CaseOpeningReward::class);
    }

    public static function fromRewardAndRequestData(CaseOpeningReward $reward, array $data)
    {
        $return = new self();

        $return->user_id = $reward->case_opening->user->id;
        $return->case_opening_id = $reward->case_opening->id;
        $return->reward_id = $reward->id;
        $return->twitch_user_id = $data['user_id'] ?? null;
        $return->twitch_user_name = $data['user_name'] ?? null;
        $return->twitch_reward_name = $data['reward']['title'] ?? null;
        $return->twitch_reward_cost = $data['reward']['cost'] ?? null;

        return $return;
    }
}
