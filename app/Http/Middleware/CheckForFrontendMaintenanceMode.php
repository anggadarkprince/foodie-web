<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;

class CheckForFrontendMaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (app_setting(Setting::MAINTENANCE_FRONTEND)) {
            abort(503);
        }
        return $next($request);
    }
}
