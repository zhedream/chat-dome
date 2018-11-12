<?php
@include_once "../man.class.php";
final class Man2 extends man
{
    // 成员变量
    public static $Man2Count=0; // 静态变量
    var $name;
    var $color;
    var $age;
    // 成员函数
    function eat(){

        echo $this->name."在吃清真饭<br>";
    }

    public function __construct($name="",$color="黄色",$age=0){
        
        // man::$Man2Count+=1;
        self::$Man2Count+=1;
        $this->name= $name==""? ('ManTwo'.self::$Man2Count) : $name;
        $this->color=$color;
        $this->age=$age;
        
        echo "{$this->name}被创造<br>";
    } 
}


/*

    最终类 能 去 继承


*/





?>