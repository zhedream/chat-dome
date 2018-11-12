<?php

class man
{
    // 成员变量
    public static $manCount=0; // 静态变量
    var $name;
    var $color;
    var $age;
    // 成员函数
    function eat(){

        echo $this->name."在吃清真饭<br>";
    }

        function toBind_Dog($obj_dog){

        $obj_dog->bind($this);

    }

    public function __construct($name="",$color="黄色",$age=0){
        
        // man::$manCount+=1;
        self::$manCount+=1;
        $this->name= $name==""? ('man'.self::$manCount) : $name;
        $this->color=$color;
        $this->age=$age;
        
        echo "{$this->name}被创造<br>";
    } 
        // 没有 该 魔术变量 会报错
    function __get($n){
        return "$n,不存在<br>";
    }
}










?>