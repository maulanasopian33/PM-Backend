<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Console\Output\ConsoleOutput;

class LogRequests
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // Log request information
        $log = [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'status' => $response->status(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'referer' => $request->headers->get('referer')
        ];

        Log::channel('routeRequest')->info('Request', $log);

        return $response;
    }
}
