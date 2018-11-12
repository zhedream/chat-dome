<?php
@include_once "./man.class.php"; 
// 抑制符 include_once  自动加载类 已经包含   抑制错误 程序 继续
class SurperMan extends man
{
    
    var $name;
    var $speed;

    function fly(){

        echo $this->name."飞起来了<br>";
    }
        // 重写 与 多态
    function eat(){

        echo $this->name."在吃炒饭<br>";
    }
        // 
    public function __construct($name="",$color="黄色",$age=0){
        
        // man::$manCount+=1;
        self::$manCount+=1;
        $this->name= $name==""? ('sman'.self::$manCount) : $name;
        $this->color=$color;
        $this->age=$age;
        
        echo "{$this->name}被创造<br>";
    }
}

/*

    继承
    会 自动加载

    成员变量 可重写

    函数 可重写

    构造 函数 可重写




*/














?>