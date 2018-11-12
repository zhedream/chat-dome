<?php

namespace app\Middlewares;

use Closure;

class PassErrTime
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request,Closure $next){
        if(!isset($_SESSION['user_id'])){
            // echo '前置中间件:未登陆';
            // dd($_SESSION);
            // return message('请重新登陆',1,'/');
        }else{
            
            // echo '前置中间件:已登陆';
        }
        return $next();
    }
}
