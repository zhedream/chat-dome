<?php

namespace app\Middlewares;

use Closure;

class CheckManage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request,Closure $next){   
        
        return $next();
    }
}
