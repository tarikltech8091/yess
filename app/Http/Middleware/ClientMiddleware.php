<?php

namespace App\Http\Middleware;

use Closure;

class ClientMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!(\Auth::check()) || (\Auth::user()->user_type != "client"))
        {
            if ($request->ajax())
            {
                return response('Unauthorized.', 401);
            }
            else
            {

                \Session::flash('errormessage','Invalid Request');
                // \Session::put('client_login_url',\URL::current());
                \Session::put('client_login_url',\URL::previous());
                return redirect()->guest('/sign-in/page');
            }
        }
        return $next($request);
    }
}
