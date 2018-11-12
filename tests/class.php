<?php

class Boy{

    public $name;
    function __construct($name){
        $this->name = $name;
    }
    function say(){
        echo $this->name."say";
    }
}

$a = new Boy('小米');
$b = new Boy('小明');

$a->say2 = function(){
    echo $this->name."mading say";
};
$a->say();
$a->say2();