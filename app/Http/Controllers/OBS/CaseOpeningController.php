<?php

namespace App\Http\Controllers\OBS;

use App\Enum\CaseOpeningType;
use App\Exceptions\SelectRewardException;
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

        return view(CaseOpeningType::from($opening->type)->getView(), [
            'opening' => $opening,
        ]);
    }

    public function redeem(Request $request, string $view_key): JsonResponse
    {
        $opening = CaseOpening::where('view_key', '=', $view_key)->firstOrFail();

        try {
            return response()->json(
                match(CaseOpeningType::from($opening->type)) {
                    CaseOpeningType::CASE => $this->redeemCase($opening, $request->all()),
                    CaseOpeningType::WHEEL => $this->redeemWheel($opening, $request->all()),
                }
            );
        } catch (SelectRewardException $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'General error, please contact support. (' . $e->getMessage() . ')',
            ], 500);
        }
    }

    private function redeemCase(CaseOpening $opening, array $data): array
    {
        $rewards = [];
        for ($i = 1; $i <= 42; $i++) {
            $rewards[$i] = $opening->randomRewardWeighted;
        }

        $winner = $rewards[34];

        CaseOpeningRedemption::fromRewardAndRequestData($winner, $data)->save();

        return [
            'html' => view('obs.case_opening.rewards', [
                'rewards' => $rewards,
            ])->render(),
            'data' => [
                'case_reward_name' => $winner->name,
                'case_reward_streamerbot_action_id' => $winner->streamerbot_action_id,
            ],
        ];
    }

    private function redeemWheel(CaseOpening $opening, array $data): array
    {
        $rewards = $opening->rewards;
        $winner = $opening->randomRewardWeighted;

        CaseOpeningRedemption::fromRewardAndRequestData($winner, $data)->save();

        return [
            'segments' => $rewards->map(function ($reward) use ($data, $winner) {
                return ['label' => $reward->name, 'share' => ($reward->chance()/100), 'color' => $reward->color];
            }),
            'winner' => $rewards->search($winner),
            'data' => [
                'case_reward_name' => $winner->name,
                'case_reward_streamerbot_action_id' => $winner->streamerbot_action_id,
            ],
        ];
    }
}
