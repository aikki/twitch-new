<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\CaseOpening;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CaseOpeningController extends Controller
{
    public function list(Request $request): View
    {
        return view('case_opening.list', [
            'openings' => $request->user()->case_openings,
        ]);
    }

    public function create(Request $request): View
    {
        return view('case_opening.create', []);
    }

    public function edit(Request $request, CaseOpening $case_opening): View
    {
        Gate::authorize('update', $case_opening);
        return view('case_opening.edit', [
            'opening' => $case_opening,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => [
                'required',
                'max:255',
                Rule::unique('case_openings')->where(fn (Builder $query) => $query->where('user_id', $request->user()->id))
            ],
            'streamerbot_reward_id' => [
                'required',
                'max:255',
                Rule::unique('case_openings')->where(fn (Builder $query) => $query->where('user_id', $request->user()->id))
            ]
        ]);

        $case_opening = new CaseOpening();
        $case_opening->fill($request->post());
        $case_opening->user_id = $request->user()->id;
        $case_opening->view_key = Str::random(32);
        $case_opening->is_public = $request->input('is_public', false);
        $case_opening->save();
        return Redirect::route('case_openings.list');

    }

    public function update(Request $request, CaseOpening $case_opening): RedirectResponse
    {
        Gate::authorize('update', $case_opening);
        $request->validate([
            'name' => [
                'required',
                'max:255',
                Rule::unique('case_openings')->where(fn (Builder $query) => $query->where('user_id', $request->user()->id))->ignore($case_opening->id)
            ],
            'streamerbot_reward_id' => [
                'required',
                'max:255',
                Rule::unique('case_openings')->where(fn (Builder $query) => $query->where('user_id', $request->user()->id))->ignore($case_opening->id)
            ]
        ]);

        $case_opening->fill($request->post());
        $case_opening->is_public = $request->input('is_public', false);
        $case_opening->save();
        return Redirect::route('case_openings.edit', [ 'case_opening' => $case_opening->id ])->with('message', 'Saved.');

    }
}
