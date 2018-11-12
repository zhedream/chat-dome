<?php
    // 初始 抽象类
abstract class Animal{

    public static $objsCount=0; // 静态初始值
    public $name;
    public $weight; // 单位 kg
    public $color;
        // 每个方法 必须被实现  或 继承为 子抽象类
    abstract public function eat();
    // abstract public function run();
    // abstract public function drink();
    // abstract public function jump();
    abstract public function sleep();
}







?>