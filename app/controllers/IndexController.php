<?php

namespace app\controllers;

use Firebase\JWT\JWT;

use core\Request;
use app\models\User;

class IndexController extends Controller {

    // 显示列表
    function index() {
        
        view('index.index');
    }
    function chat(){
        view('chat.chat',['token_jwt',$_SESSION['token_jwt']]);
    }

    function login(){
        $jwt_key = 'key123';
        if(isset($_COOKIE['uuu'])){
            redirect(Route('index.dologin'));
        }
        return view('login.login');
    }

    function dologin(Request $req){
        $jwt_key = 'key123';
        // $data = $req->all();dd($data);
        $remenber = $req->remember==null? false:true;

        // 登陆页 检测cookie 直接跳转登陆古
        if(isset($_COOKIE['uuu'])){
            $data = JWT::decode($_COOKIE['uuu'], $jwt_key, array('HS256'));
            $user = (new User)->where(['uname'=>$data->uname,'password'=>$data->password])->first();
        }else{
            $user = (new User)->where(['uname'=>$req->uname,'password'=>$req->password])->first();
        }
        
        // dd($user);
        if($user){
            $_SESSION['uid'] = $user['uid'];
            $_SESSION['uname'] = $user['uname'];

            $cookie = array(
                "uname" => $req->uname,
                "password" => $req->password
            );

            $token = array(
                "iat" => time(),   // 签发时间
                "exp" => mktime(0, 0, 0, date('m'), date('d')+1, date('Y')),    // 过期时间 
                "uid" => $user['uid'],
                "uname" => $user['uname'],
                "avatar" => "/img/head/2015.jpg"
            );
            $cookie_jwt = JWT::encode($cookie,$jwt_key); // 生成 JWT 的 cookie
            $token_jwt = JWT::encode($token,$jwt_key); // 生成 token
            $_SESSION['token_jwt'] = $token_jwt; // 用于 index.chat
            response()->WithCookie('uuu',$cookie_jwt,strtotime("+1 day")); // 响应设置Cookie
            dd($token);
            message('登陆成功',1,Route('index.chat'),2);
        }else{
            return message('用户不存在 或密码错误',1,Route('index.login'),2);
        }
    }
    
    function regist(){
        return view('login.regist');
    }
    
    function doregist(Request $req){
        $data = $req->all();
        $user = new User;
        $a = $user->where('uname',$req->uname)->first();
        if(!$a){
            $user->fill($data);
            $user->tel_num = '';
            $user->reg_time = date('Y-m-d G:i:s');
            $user->insert();
            return message('注册成功',1,Route('index.login'),2);
        }else{
            return message('用户已注册,忘记密码请联系 管理员',1,Route('index.login'),2);
        }
        
    }
    function test(){


    }

}

?>