<?php

use Binafy\LaravelUserMonitoring\Models\AuthenticationMonitoring;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\{assertDatabaseCount, assertDatabaseHas};

/*
 * Use `RefreshDatabase` for delete migration data for each test.
 */
uses(RefreshDatabase::class);

test('store authentication monitoring when a user login', function () {
    $user = createUser();
    auth()->login($user);

    // Assertions
    expect($user->name)
        ->toBe(AuthenticationMonitoring::first()->user->name)
        ->and('login')
        ->toBe(AuthenticationMonitoring::first()->value('action_type'));

    // DB Assertions
    assertDatabaseCount(config('user-monitoring.authentication_monitoring.table'), 1);
    assertDatabaseHas(config('user-monitoring.authentication_monitoring.table'), [
        'user_id' => $user->id,
        'action_type' => 'login',
    ]);
});

test('store authentication monitoring when a user logout', function () {
    $user = createUser();
    auth()->login($user);
    auth()->logout();

    // Assertions
    expect($user->name)
        ->toBe(AuthenticationMonitoring::first()->user->name)
        ->and('logout')
        ->toBe(AuthenticationMonitoring::query()->firstWhere('id', 2)->action_type);

    // DB Assertions
    assertDatabaseCount(config('user-monitoring.authentication_monitoring.table'), 2);
    assertDatabaseHas(config('user-monitoring.authentication_monitoring.table'), [
        'user_id' => $user->id,
        'action_type' => 'logout',
    ]);
});
