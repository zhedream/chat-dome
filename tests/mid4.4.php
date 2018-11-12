<?php
/**
 * Created by PhpStorm.
 * User: wg
 * Date: 18-6-26
 * Time: 上午11:31
 */
 
interface IDecorator
{
    public function before();
    public function after();
}
 
class Shoes implements IDecorator
{
    private $size;
    public function __construct($size)
    {
        $this->size = $size;
    }
 
    public function after()
    {
        echo "我脱了 $this->size 码的鞋子,";
    }
 
    public function before()
    {
        echo "我穿上 $this->size 码的鞋子,";
    }
 
}
 
class Clothes implements IDecorator
{
    private $color;
    public function __construct($color)
    {
        $this->color = $color;
    }
    public function before()
    {
        echo "我穿上了$this->color 颜色的衣服,";
 
    }
 
    public function after()
    {
        echo "我脱了$this->color 颜色的衣服,";
    }
 
}
 
class Tom
{
    private $thing;
    public function goOut(){
        $this->beforeGoOut();
        echo "我出门了";
        $this->afterGoOUt();
    }
 
    public function beforeGoOut(){
        foreach ($this->thing as $value){
            $value->before();
        }
    }
 
    public function afterGoOUt(){
        $temp = array_reverse($this->thing);
        foreach ($temp as $item) {
            $item->after();
        }
    }
 
    public function addThing(IDecorator $decorator){
        $this->thing[] = $decorator;
    }
}
 
$tom = new Tom();
$tom->addThing(new Clothes('红'));
$tom->addThing(new Shoes('40'));
$tom->goOut();
