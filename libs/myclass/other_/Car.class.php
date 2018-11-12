<?php


class Car
{   
    // var $call = this;
    var $name;
    private $master; // 绑定 使用者
    private $Ckey; // key 
    private $key; // key 
    public static $carCount=0; // 静态变量
    function bind($obj_call){
        if($this->master==false ||$this->master==''){
            $this->master=$obj_call;
            echo "{$obj_call->name}绑定了{$this->name}<br>";
        }else {
            echo "{$obj_call->name}绑定{$this->name}失败，已被{$this->master->name}绑定<br>";
        }
    }

    function move(){
        if($this->master==false){
           echo "{$this->name}未绑定用户,不能移动<br>";
        }else {
             echo "{$this->name}移动了<br>";
        }
    }

    public function __construct($name=''){
        car::$carCount+=1;
        $this->name= $name==""? ('car'.car::$carCount) : $name;
        echo "{$this->name}被创造<br>";
    }
    function __destruct(){
        car::$carCount-=1;
    }
        // 没有 该 魔术变量 会报错
    function __get($n){
        return "$n,不存在<br>";
    }

}







?>