<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\EmployeeClientVisit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;

class CheckIncompleteVisits
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Skip check for guests, API routes, or specific routes like login
        if (!Auth::check() || $this->shouldSkipCheck($request)) {
            return $next($request);
        }

        $user = Auth::user();

        // Only apply to employees
        if ($user->role !== 'employee') {
            return $next($request);
        }

        // Check for incomplete visits that need justification
        // Only for dates BEFORE today
        $incompleteVisits = EmployeeClientVisit::where('employee_id', $user->id)
            ->whereDate('created_at', '<', Carbon::today())
            ->needsJustification()
            ->exists();

        // If there are incomplete visits without proper justification, redirect to justification page
        if ($incompleteVisits) {
            // Don't redirect if we're already on the justification page
            if (!$request->is('incomplete-visits-justification') && !$request->is('incomplete-visits-justification/*')) {
                return redirect()->route('incomplete.visits.justification');
            }
        }

        return $next($request);
    }

    /**
     * Determine if the check should be skipped for specific routes.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function shouldSkipCheck(Request $request)
    {
        // Skip for logout, login, and API routes
        $skipRoutes = [
            'logout',
            'login',
            'register',
        ];

        // Skip for API routes
        if ($request->is('api/*')) {
            return true;
        }

        // Skip for specific named routes
        foreach ($skipRoutes as $route) {
            if ($request->is($route) || Route::currentRouteName() == $route) {
                return true;
            }
        }

        return false;
    }
}