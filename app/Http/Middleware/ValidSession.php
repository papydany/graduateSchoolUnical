<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;


class ValidSession
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

    $result = session('key'); 

if($result == null || $result->name == null)
{
  Auth::guard('web')->logout();
  session()->invalidate();
  return redirect('login');
}
        return $next($request);
    }
}
