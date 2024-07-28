<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\CaseOpening;
use App\Models\User;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PublicRewardChancesController extends Controller
{
    public function __invoke(Request $request, string $username): View
    {
        $user = User::where('name', '=', $username)->firstOrFail();
        $openings = $user->public_case_openings;
        if ($openings->isEmpty()) {
            throw new NotFoundHttpException();
        }

        return view('public.chances', [
            'openings' => $openings,
        ]);
    }
}
