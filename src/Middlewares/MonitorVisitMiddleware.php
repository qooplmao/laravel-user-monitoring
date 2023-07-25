<?php

namespace Binafy\LaravelUserMonitoring\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Agent;

class MonitorVisitMiddleware
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
        $agent = new Agent();
        $guard = config('user-monitoring.user.guard', 'web');
        $exceptPages = config('user-monitoring.visit_monitoring.expect_pages', []);

        if (empty($exceptPages) || !$this->checkIsExpectPages($request->path(), $exceptPages)) {
            // Store visit
            DB::table(config('user-monitoring.visit_monitoring.table'))->insert([
                'user_id' => auth($guard)->id(),
                'browser_name' => $agent->browser(),
                'platform' => $agent->platform(),
                'device' => $agent->device(),
                'ip' => $request->ip(),
                'page' => $request->url(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return $next($request);
    }

    /**
     * Check request page are exists in expect pages.
     *
     * @param  string $page
     * @param  array $exceptPages
     * @return bool
     */
    private function checkIsExpectPages(string $page, array $exceptPages)
    {
        return collect($exceptPages)->contains($page);
    }
}