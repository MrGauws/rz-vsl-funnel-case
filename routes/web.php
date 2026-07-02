<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\FunnelController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Laravel-style route map
|--------------------------------------------------------------------------
|
| The runnable demo is dependency-light PHP because the interview machine
| has PHP 7.4. This file shows how the same funnel would be wired in Laravel.
|
*/

Route::redirect('/', '/vsl?affiliate_id=demo-affiliate&click_id=click-123&campaign=interview-prep');

Route::get('/vsl', [FunnelController::class, 'vsl'])->name('funnel.vsl');
Route::get('/checkout', [FunnelController::class, 'checkout'])->name('funnel.checkout');
Route::get('/upsell', [FunnelController::class, 'upsell'])->name('funnel.upsell');
Route::get('/downsell', [FunnelController::class, 'downsell'])->name('funnel.downsell');
Route::get('/thank-you', [FunnelController::class, 'thankYou'])->name('funnel.thank-you');
Route::get('/members', [FunnelController::class, 'members'])->name('funnel.members');

Route::post('/webhook/purchase', [FunnelController::class, 'webhook'])
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class])
    ->name('webhooks.purchase');

Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
Route::get('/qa', [AdminController::class, 'qa'])->name('admin.qa');

