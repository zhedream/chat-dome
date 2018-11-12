<?php
@include_once "../abstruct/animal.class.php";
    // 继承 抽象 类
class dog extends DogClass
{

    public static $objsCount=0; // 静态初始值
    public $name;
    public $weight; // 单位 kg
    public $color;
    // public static $objsCount =0;
    private $master; // 绑定 使用者
            // 必须 实现 所有 抽象类链 方法,子抽象类可以 提前 实现
        public function test(){

        }
         public function eat(){
                // 当前 类  被继承  子类调用 此函数   返回的是  当前类 的值
            return self::$weight+=1;
            echo "{$this->name}正在吃，涨了1 kg<br>";
        }
            // 当前 类  被继承  子类调用 此函数   返回的是  子类 的值
         public function run(){
            static::$weight-=.05;
            echo "{$this->name}在跑，消耗 0.05 kg<br>";
        }

    function bind($obj_call){
        if($this->master==false ||$this->master==''){
            $this->master=$obj_call;
            echo "{$obj_call->name}绑定了{$this->name}<br>";
        }else {
            echo "{$obj_call->name}绑定{$this->name}失败，已被{$this->master->name}绑定<br>";
        }
    }

    function master(){
        if($this->master==false ||$this->master==''){
            echo "{$this->name}没有主人<br>";
        }else {
            echo "{$this->name}他的主人是{$this->master->name}<br>";
                // self::$master 用于 静态类
        }

    }

    public function __construct($name="",$color="白色"){
    
        dog::$objsCount+=1;
        $this->name= $name==""? ('dog'.dog::$objsCount) : $name;
        echo "{$this->name}被创造<br>";
        $this->color=$color;
        
    }

    function __destruct(){
        echo "{$this->name}被销毁<br>";
        dog::$objsCount-=1;
    }
}




/*


总结：代表类的关键字有几个？
self：代表本类，用来访问本类的静态成员。（早期绑定：写在哪个类中就代表哪个类）
static：代表调用这个对象的类，用来访问静态成员。（后期绑定：哪个对象调用的就代表哪个类）
parent：代表父类，用来访问父类中的成员。（早期绑定：代表所在类的父类或者祖先类）
扩展： $this 代表什么？
代表调用这个方法的对象。用来访问类中的普通成员

*/




?>