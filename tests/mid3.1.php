<?php
// 框架核心应用层
$application = function($name) {
    echo "-- {$name} APP--\n";
};
 
// 前置校验中间件
$auth = function($request,$handler) {
    var_dump($handler instanceof Closure) ;
    $request++;
    echo "--{$request} 前置1--\n";
    // $handler($request);die;
        return $handler($request,$handler);
};
// 前置校验中间件2
$auth2 = function($request,$handler) {
    var_dump($handler instanceof Closure) ;

    $request++;
    echo "--{$request} 前置2--\n";
    return $handler($request,$handler);
};
 
// 后置日志中间件
$log = function($request,$handler)  {
    // die;
    var_dump($handler instanceof Closure) ;
    $request++;
    $return = $handler($request,$handler);
    // die;
    echo "--{$request} 后置1--\n";
    return $return;
};


function handle($request, Closure $next)
    {

        echo json_encode(debug_backtrace());die;

        if(!session('uid')){


            if($request->ajax()){

                return response([
                    'errno'=>'1001',
                    'errmsg'=>'必须登陆',
                ]);
            }

            return redirect()->route('Login');
        }
        return $next($request);
    }

    $a = function() {
         handle();
    };
    
// 中间件栈
$stack = [];
 
// 打包 排序
function pack_middleware($handler, $stack){

    return function($request) use($handler, $stack){
    
        foreach (array_reverse($stack) as $key => $middleware) 
        {   
            // var_export($middleware,false);die;
            $handler = $middleware($request,$handler); // 把 handler( APP ) 放入 一个 中间件 打包 返回 新的 中间件们 + APP 的 新栈堆 
        }
        return $handler; // 返回 调用 栈堆
    };
}
 
// 注册中间件
// 这里用的都是全局中间件，实际应用时还可以为指定路由注册局部中间件
$stack[] = $auth;
$stack[] = $auth2;
// $stack[] = $log;
 
$run = pack_middleware($application, $stack);
// var_export($run);die;
$run(1);
// $run('Laravle');
