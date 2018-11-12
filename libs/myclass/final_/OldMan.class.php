<?php

    // 最终类 
final class OldMan
{
    // 成员变量
    public static $OldManCount=0; // 静态变量
    var $name;
    var $color;
    var $age;
    // 成员函数
    function eat(){

        echo $this->name."在吃老饭<br>";
    }

    public function __construct($name="",$color="黄色",$age=60){
        
        // man::$OldManCount+=1;
        self::$OldManCount+=1;
        $this->name= $name==""? ('OldMan'.self::$OldManCount) : $name;
        $this->color=$color;
        $this->age=$age;
        
        echo "{$this->name}被创造<br>";
    } 

        // 没有 该 魔术变量 会报错
    function __get($n){
        return "$n,不存在<br>";
    }
}



/*

    也叫做最终类,不能被继承,只能用来实例化对象。

    ? 能去 继承 吗


 */







?>