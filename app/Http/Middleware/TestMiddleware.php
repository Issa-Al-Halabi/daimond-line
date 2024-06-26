<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
     // $response = $next($request);
        Log::info('Request:', [
                   'URI' => $request->getUri(),
                 'METHOD' => $request->getMethod(),
                 'REQUEST_BODY' => $request->all(),
                 //'RESPONSE' => $response->getContent(),
        
          		 'test' => $_SERVER['REMOTE_ADDR']
        ]);
 
        return $next($request);
    }
}
