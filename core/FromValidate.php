<?php

namespace core;

use core\Request;

// 验证表单器
class FromValidate {

    private static $_instance = null; // 实例化单例 对象

    public $errInputMessages = [];

    private function __construct() {
    }

    private function __clone() {
    }

    public static function getInstance() {
        if (self::$_instance == null) {
            self::$_instance = new self();
            return self::$_instance;
        }
        return self::$_instance;
    }

    function verify(Request $request) {

        $rules = $request->rules();
        // dd($rules);
        foreach ($rules as $key => $val) {
            // var_dump($request->all());
            $tem = $request->$key; // 获取 表单 相对应键 的值

            if ($key[0] == '&') {
                // 传入全部参数  特殊验证规则

                foreach ($val as $k => $v) {
                    if (is_string($v)) {
                        if ($v == 'required') {

                        }
                    }
                    if (is_callable($v)) {

                        $re = $v($request, $request->all());
                        if ($re) {
                            echo '函数验证' . $key . "结果:" . $re . lm;
                            // 有消息便是 坏消息
                            $this->errInputMessages[$key] = $re;
                        }
                    }
                }

            } else {

                foreach ($val as $k => $v) {
                    if (is_string($v)) {
                        if ($v == 'required') {

                        }
                    }
                    if (is_callable($v)) {

                        $re = $v($tem);
                        if ($re) {
                            echo '函数验证' . $key . "结果:" . $re . lm;
                            $this->errInputMessages[$key] = $re;
                            // return ;
                        }
                    }
                }
            }
        }

        //
        if($this->errInputMessages)
            return $this->errInputMessages;
        
        return true;
    }

}


?>