<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CaseOpeningReward extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'weight',
        'color',
        'image_url',
        'streamerbot_action_id',
    ];

    public function case_opening(): BelongsTo
    {
        return $this->belongsTo(CaseOpening::class);
    }

    public function parent(): BelongsTo
    {
        return $this->case_opening();
    }

    public function chance(): float
    {
        return ($this->weight / $this->parent->sumWeights()) * 100;
    }

    public function getChanceAttribute()
    {
        return mb_str_pad(number_format($this->chance(), 2) . '%', 6, " ", STR_PAD_LEFT);
    }
}
