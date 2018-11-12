<?php


class iman implements I_ManSay,I_eat{
    var $name = "iman啊啊";
    public function say(){
        echo "iman实现ManSay接口<br>";
    }
    function eat(){
        echo "iman实现eat接口<br>";

    }
    function __construct(){
        $this->say();
        $this->eat();
        echo "iman实现所有接口<br>";
    }
        // 显示 接口 常量
    function show(){
        echo iman::PI;
    }
// 魔术变能量
    function __get($n){
        echo $n."->get:不存在<br>";
    }
        // 属性 重载 不设置 默认 重载
    function __set($n,$val){
        var_dump($n,$val);
        echo $n."->set:不存在<br>";
    }
        // 使用 isset 访问一个不可访问的属性时调用。
    function __isset($n){
        echo $n."->isset私有属性不可访问<br>";
    }
    // 当调用 unset 删除一个不可访问的属性时调用。
    function __unset($n){
        var_dump($n);
        echo $n."->isset私有不可删除<br>";
    }

    function __call($n,$p){
        var_dump($n,$p);
        echo $n."方法,不存在<br>";
    }
        // 类中不存在的 静态方法时，
    static function __callStatic($n,$p){
        var_dump($n,$p);
        echo $n."静态方法,不存在<br>";
    }
}



?>