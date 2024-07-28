<?php

use App\Http\Controllers\OBS\CaseOpeningController as OBSCaseOpeningController;
use App\Http\Controllers\Web\CaseOpeningController;
use App\Http\Controllers\Web\CaseOpeningRewardController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\PublicRewardChancesController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::get('/case_openings', [CaseOpeningController::class, 'list'])->name('case_openings.list');
    Route::get('/case_openings/create', [CaseOpeningController::class, 'create'])->name('case_openings.create');
    Route::get('/case_openings/edit/{case_opening}', [CaseOpeningController::class, 'edit'])->name('case_openings.edit');
    Route::post('/case_openings/store', [CaseOpeningController::class, 'store'])->name('case_openings.store');
    Route::patch('/case_openings/update/{case_opening}', [CaseOpeningController::class, 'update'])->name('case_openings.update');

    Route::get('/case_openings/{case_opening}/rewards/create', [CaseOpeningRewardController::class, 'create'])->name('case_openings.rewards.create');
    Route::get('/case_openings/rewards/edit/{reward}', [CaseOpeningRewardController::class, 'edit'])->name('case_openings.rewards.edit');
    Route::post('/case_openings/{case_opening}/rewards/store', [CaseOpeningRewardController::class, 'store'])->name('case_openings.rewards.store');
    Route::patch('/case_openings/rewards/update/{reward}', [CaseOpeningRewardController::class, 'update'])->name('case_openings.rewards.update');
    Route::get('/case_openings/rewards/pause/{reward}/{state}', [CaseOpeningRewardController::class, 'pause'])->name('case_openings.rewards.toggle_pause');
});

Route::get('/chances/{username}', PublicRewardChancesController::class)->name('public.case_openings.rewards_chances');

Route::get('/obs/case_opening/{view_key}', [OBSCaseOpeningController::class, 'show'])->name('obs.case_openings.show');
Route::post('/obs/case_opening/{view_key}/redeem', [OBSCaseOpeningController::class, 'redeem'])
    ->withoutMiddleware([VerifyCsrfToken::class])
    ->name('obs.case_openings.redeem');

require __DIR__.'/auth.php';
