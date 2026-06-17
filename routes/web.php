<?php

use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/demo-login/{email}', function (string $email) {
    abort_unless(config('settings.demo.enabled'), 404);

    $account = collect(config('settings.demo.accounts'))->firstWhere('email', $email);

    abort_if($account === null, 403);

    $user = User::where('email', $email)->firstOrFail();

    Auth::login($user);
    request()->session()->regenerate();

    return redirect(Filament::getPanel($account['panel'])->getUrl());
})->name('demo.login');
