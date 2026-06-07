<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class XSS
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
    
    
    $userInput = $request->all();
array_walk_recursive($userInput, function (&$userInput) {
$userInput = strip_tags($userInput);
});
$request->merge($userInput);
        return $next($request);
    }
    
    
}
