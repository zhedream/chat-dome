<?php

namespace app\Requests;

class LoginRequest extends FromRequest
{


    public function authorize(){
        return true;
    }
    
    public function rules(){

        return [
            '&username'=>[
                function($request,$val){
                    // if()
                    $username = $request->popAllData('username');
                    $ex = preg_match('/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/',$username);
                    $ex2 = preg_match('/^(13[0-9]|14[5|7]|15[0|1|2|3|5|6|7|8|9]|18[0|1|2|3|5|6|7|8|9])\d{8}$/',$request->username);
                    if($ex){
                        // email
                        $request->setAllData('email',$username);
                    }else if($ex2){
                        // 手机号
                        $request->setAllData('phone',$username);
                    }else{
                        // echo '用户名';
                        // $request->setAllData('username',$username);
                        return '请输入正确的手机号或邮箱账号';
                    }
                }
            ],
            'password'=>[
                'password',
                function($val){
                    if(!isset($val) || $val=='')
                        return '密码是必填项';
                }
            ],
            
        ];
    }


}