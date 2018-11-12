<?php



class GetError extends \Exception{
    static $File;
    static $Message;
    static $Code;
    static $Line;
    static $Fn;
        // 1.错误信息,2.错误编码,3.错误对象 /限制参数  array/Student/callable
    function __construct($Message,$Code=1,$obj=null){

        self::$File = parent::getFile();
        self::$Message = $Message;
        self::$Code = $Code;
        self::$Line = parent::getLine();
        self::$Fn = __FUNCTION__;
    }

    function getF(){
       echo self::$File;
    }
    function getM(){
       echo self::$Message;
    }
    function getC(){
       echo self::$Code;
    }
    function getL(){
        echo self::$Line;
    }





}












?>