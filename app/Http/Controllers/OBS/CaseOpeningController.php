<?php

namespace App\Http\Controllers\OBS;

use App\Http\Controllers\Controller;
use App\Models\CaseOpening;
use App\Models\CaseOpeningRedemption;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CaseOpeningController extends Controller
{
    public function show(Request $request, string $view_key): View
    {
        $opening = CaseOpening::where('view_key', '=', $view_key)->firstOrFail();
        return view('obs.case_opening.show', [
            'opening' => $opening,
        ]);
    }

    public function redeem(Request $request, string $view_key): JsonResponse
    {
        $opening = CaseOpening::where('view_key', '=', $view_key)->firstOrFail();

        $rewards = [];
        for ($i = 1; $i <= 42; $i++) {
                $rewards[$i] = $opening->randomRewardWeighted;
        }

        $winner = $rewards[34];

        CaseOpeningRedemption::fromRewardAndRequestData($winner, $request->all())->save();

        return response()->json([
            'html' => view('obs.case_opening.rewards', [
                'rewards' => $rewards,
            ])->render(),
            'data' => [
                'case_reward_name' => $winner->name,
                'case_reward_streamerbot_action_id' => $winner->streamerbot_action_id,
            ],
        ]);
    }
}
