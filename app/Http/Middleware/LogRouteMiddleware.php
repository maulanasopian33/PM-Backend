<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class LogRouteMiddleware
{
    public function handle($request, Closure $next)
    {
        $route = $request->route();
        $uri = $request->getPathInfo();
        $method = $request->method();
        $action = $route->getActionName();
        $parameters = $route->parameters();

        Log::info("Request to $uri [$method] handled by $action with parameters: " . json_encode($parameters));
        // dd("Request to $uri [$method] handled by $action with parameters: " . ($parametersjson_encode));

        return $next($request);
    }
}
