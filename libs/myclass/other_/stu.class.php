<?php


//  重载 foreach
class stu implements Iterator
{
    var $a = "aa";
    public $arr = ["a"=>"值1","b"=>"值2","c"=>"值3"];

    function rewind(){
        reset($this->arr);
    }
    function current(){
        return current($this->arr);
    }
    function next(){
        next($this->arr);
    }
    function key(){
        return key($this->arr);
    }
    function valid(){
        static $count = 0;
        $count++;
        return ($count <= count($this->arr));
    }
    // function 

}






?>