<?php

namespace core\Middlewares;

use Closure;
use core\FromValidate;
// 表单中间件
class ValidateFromInput {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        $ex = method_exists($request, 'authorize');
        $ex2 = method_exists($request, 'rules');
        // dd($request);
        if ($ex && $ex2) {
            echo '前置中间件:需要验证表单<br>';
            
            $auth = $request->authorize();
            if ($auth) {

                $validate = FromValidate::getInstance();
                $data = $validate->verify($request);
                if($data!==true){
                    var_dump($data);die;
                    response()->setInputErrs($data);
                    return back();
                }

            } else {

                echo('权限不足');
                return;
            }

        }

        return $next();
    }
}
