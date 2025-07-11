<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SetLayout
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role === 'admin') {
                view()->share('layout', 'layouts.admin.app');
            } else if($user->role === 'responder'){
                view()->share('layout', 'layouts.responder.app');
            } else {
                view()->share('layout', 'layouts.user.app');
            }
        } else {
            view()->share('layout', 'layouts.app');
        }

        return $next($request);
    }
} 