<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\CaseOpening;
use App\Models\CaseOpeningReward;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CaseOpeningRewardController extends Controller
{
    public function create(Request $request, CaseOpening $case_opening): View
    {
        return view('case_opening.rewards.create', [
            'opening' => $case_opening,
        ]);
    }

    public function edit(Request $request, CaseOpeningReward $reward): View
    {
        return view('case_opening.rewards.edit', [
            'reward' => $reward,
        ]);
    }

    public function store(Request $request, CaseOpening $case_opening): RedirectResponse
    {
        $request->validate([
            'name' => [
                'required',
                'max:255',
                Rule::unique('case_opening_rewards')->where(fn (Builder $query) => $query->where('case_opening_id', $case_opening->id)),
            ],
            'weight' => [
                'required',
                'integer',
            ],
            'image_url' => [
                'max:255',
            ],
            'streamerbot_action_id' => [
                'max:255',
            ],
        ]);

        $reward = new CaseOpeningReward();
        $reward->fill($request->post());
        $reward->case_opening_id = $case_opening->id;
        $reward->save();
        return Redirect::route('case_openings.edit', [ 'case_opening' => $case_opening->id ]);

    }

    public function update(Request $request, CaseOpeningReward $reward): RedirectResponse
    {
        $request->validate([
            'name' => [
                'required',
                'max:255',
                Rule::unique('case_opening_rewards')->where(fn (Builder $query) => $query->where('case_opening_id', $reward->parent->id))->ignore($reward->id),
            ],
            'weight' => [
                'required',
                'integer',
            ],
            'image_url' => [
                'max:255',
            ],
            'streamerbot_action_id' => [
                'max:255',
            ],
        ]);

        $reward->fill($request->post());
        $reward->save();
        return Redirect::route('case_openings.edit', [ 'case_opening' => $reward->parent->id ]);

    }

    public function pause(Request $request, CaseOpeningReward $reward, bool $state): RedirectResponse
    {
        $reward->is_active = !$state;
        $reward->save();
        return Redirect::route('case_openings.edit', [ 'case_opening' => $reward->parent->id ]);

    }
}
