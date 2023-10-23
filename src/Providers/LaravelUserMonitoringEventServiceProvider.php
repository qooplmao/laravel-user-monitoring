<?php

namespace Binafy\LaravelUserMonitoring\Providers;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Jenssegers\Agent\Agent;

class LaravelUserMonitoringEventServiceProvider extends EventServiceProvider
{
    public function boot()
    {
        // Login Event
        if (config('user-monitoring.authentication_monitoring.on_login', false)) {
            Event::listen(fn(Login $event) => $this->recordEvent('login'));
        }

        // Logout Event
        if (config('user-monitoring.authentication_monitoring.on_logout', false)) {
            Event::listen(fn(Login $event) => $this->recordEvent('logout'));
        }
    }

    /**
     * Insert data.
     *
     * @param  string $guard
     * @return void
     */
    private function recordEvent(string $actionType): void
    {
        $agent = new Agent();
        $guard = config('user-monitoring.user.guard');
        $table = config('user-monitoring.authentication_monitoring.table');

        DB::table($table)->insert([
            'user_id'       => auth($guard)->id(),
            'action_type'   => $actionType,
            'browser_name'  => $agent->browser(),
            'platform'      => $agent->platform(),
            'device'        => $agent->device(),
            'ip'            => request()->ip(),
            'page'          => request()->url(),
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
    }
}
