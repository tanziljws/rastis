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
        // Check if this is an AJAX/API request
        $isAjax = $request->expectsJson() || 
                 $request->ajax() || 
                 $request->wantsJson() ||
                 $request->header('X-Requested-With') === 'XMLHttpRequest' ||
                 $request->header('Accept') === 'application/json' ||
                 str_starts_with($request->path(), 'admin/api');
        
        if (!session('admin_token') || !session('admin_user')) {
            // For AJAX/fetch/XHR requests, ALWAYS return JSON response - NEVER redirect
            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Please login.',
                    'redirect' => route('admin.login')
                ], 401)
                ->header('Content-Type', 'application/json')
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');
            }
            
            // Only redirect for regular page requests
            return redirect()->route('admin.login');
        }

        return $next($request);
    }
}
