<?php

namespace Binafy\LaravelUserMonitoring\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Agent;

class VisitMonitoringMiddleware
{
    /**
     * Handle.
     *
     * @param  Request $request
     * @param  Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next)
    {
        if ((bool) config('user-monitoring.visit_monitoring.turn_on')) {
            $this->recordVisit($request);
        }
        
        return $next($request);
    }
    
    private function recordVisit(Request $request): void
    {
        if ($this->shouldSkipVisit($request)) {
            return;
        }

        $agent = new Agent();
        $guard = config('user-monitoring.user.guard', 'web');

        // Store visit
        DB::table(config('user-monitoring.visit_monitoring.table'))->insert([
            'user_id'       => auth($guard)->id(),
            'browser_name'  => $agent->browser(),
            'platform'      => $agent->platform(),
            'device'        => $agent->device(),
            'ip'            => $request->ip(),
            'page'          => $request->url(),
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
    }

    /**
     * Check whether the request matches any of the patterns in the should_skip list
     *
     * @param Request $request $request
     * @return bool
     */
    private function shouldSkipVisit(Request $request): bool
    {
        $shouldSkip = config('user-monitoring.visit_monitoring.should_skip', []);
        
        return $request->is($shouldSkip) || $request->routeIs($shouldSkip);
    }
}
