<?php

class robot
{   
    public $test = 'test';
    public $name;
    public $color;
    public $elect;
    public $isOn = true;
    public static $rCount=0; // 静态初始值
    public $arr = ["a"=>"值1","b"=>"值2","c"=>"值3"];
        // 怎么设置 使 $isOn == true 时

    public function cook(){
        if($this->isOn == true){
            echo "{$this->name}在煮饭<br>";
        }else {
            echo "{$this->name}未启动,不能煮饭<br>";
        }
    }

    function clean(){
        if($this->isOn == true){
            echo "{$this->name}在打扫<br>";
        }else {
            echo "{$this->name}未启动,不能打扫<br>";
        }
            
    }

    function calc($m,$n){
        echo "{$this->name}正在计算<br>";
        $sum =0;
        for($i=$m;$i<=$n;$i++)
            $sum+=$i;
        echo "{$sum}<br>";
    }

    function on(){
        //z
        if(!$this->isOn)
            $this->isOn=!$this->isOn;
    }
    function off(){
        if($this->isOn)
            $this->isOn=!$this->isOn;
    }
    function tobind($obj_car){

        $obj_car->bind($this);

    }

    public function __construct($name="",$color="白色",$elect=100,$isOn=0){
        
        robot::$rCount+=1;
        $this->name= $name==""? ('robot'.robot::$rCount) : $name;
        echo "{$this->name}被创造<br>";
        $this->color=$color;
        $this->elect=$elect;
        $this->isOn=$isOn;
        
    }

    function __clone(){
        echo "{$this->name}被克隆<br>";
        robot::$rCount+=1;
        // $name = "大明";2
    }

    function __destruct(){
        echo "{$this->name}被销毁<br>";
        robot::$rCount-=1;
    }
        // 没有 该 魔术变量 会报错
    function __get($n){
        return "$n,不存在<br>";
    }

    function __invoke(){
        echo "robot对象当函数使用了<br>";
    }
    function __toString(){
        echo "robot 被单做字符串<br>";
        return "robot不是字符串<br>";
    }
    function __sleep(){
        echo "对象{$this->name},被序列化<br>";
        // return ["属性1","属性2"];
        // 重写 sleep 如果 需要 序列化什么属性 就放回 什么 属性 名
        return ["name"];

    }
    function test(){
        echo "当前方法:".__METHOD__."<br>";
        echo "当前类名:".__CLASS__."<br>";
    }
    function __wakeup(){
            // 常用于 mysql 连接
        echo "{$this->name},苏醒了<br>";
        // echo ;
    }

}




?>
