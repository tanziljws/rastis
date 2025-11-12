<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WebAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!session('admin_token') || !session('admin_user')) {
            // For AJAX/fetch requests, return JSON response instead of redirect
            // Check for AJAX indicators: expectsJson, wantsJson, ajax(), or X-Requested-With header
            if ($request->expectsJson() || 
                $request->ajax() || 
                $request->wantsJson() ||
                $request->header('X-Requested-With') === 'XMLHttpRequest' ||
                $request->header('Accept') === 'application/json') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Please login.',
                    'redirect' => route('admin.login')
                ], 401)->header('Content-Type', 'application/json');
            }
            
            return redirect()->route('admin.login');
        }

        return $next($request);
    }
}
