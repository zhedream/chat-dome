<?php
    // 子 抽象类 可以有 方法体
abstract class DogClass extends Animal
{
    // public static $objsCount=0; // 静态初始值
    // public $name;
    // public $weight; // 单位 kg
    // public $color;
        // 未实现的 方法  必须在 子抽象类 实现 或 普通类 实现
    abstract public function test();
        // 实现方法
    public function eat(){

    }
        // 子 抽象类 可以实现 初始抽象类 方法 
    public function sleep(){
        echo "狗睡觉";
    }



}







?>