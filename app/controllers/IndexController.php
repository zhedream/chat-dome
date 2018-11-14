<?php

namespace app\controllers;

use core\Request;
use app\models\User;

class IndexController extends Controller {

    // 显示列表
    function index() {
        
        view('index.index');
    }
    function chat(){
        view('chat.chat');
    }

    function login(){

        return view('login.login');
    }

    function dologin(Request $req){
        $data = $req->all();
        // dd($data);
        $user = (new User)->where(['uname'=>$req->uname,'password'=>$req->password])->first();
        // dd($user);
        if($user){
            $_SESSION['uid'] = $user['uid'];
            $_SESSION['uname'] = $user['uname'];
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