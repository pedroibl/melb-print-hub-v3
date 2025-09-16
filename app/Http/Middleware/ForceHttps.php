<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceHttps
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Force HTTPS detection for Heroku
        if (config('app.env') === 'production') {
            // Set the scheme to HTTPS
            $request->server->set('HTTPS', 'on');
            $request->server->set('HTTP_X_FORWARDED_PROTO', 'https');
            $request->server->set('HTTP_X_FORWARDED_SSL', 'on');
            
            // Force the request to be treated as secure
            $request->setTrustedProxies(['*'], Request::HEADER_X_FORWARDED_ALL);
        }

        return $next($request);
    }
}
