<?php

namespace core;

//use \ReflectionParameter;
use \ReflectionMethod;

class ReflexMethod extends ReflectionMethod {

    var $params; // 所有参数 re 对像
    var $paramNames = [
        // 'name'=>[
        //     'position'=>'',
        //     'typeName'=>'',
        //     'hasType'=>false,
        // ],

    ];

    function __construct($class, $name) {
        parent::__construct($class, $name);
        $this->params = $this->getParameters();
        $this->analysis();
        $this->_after_construct();
    }

    private function analysis() {
        // var_dump($this->params);
        foreach ($this->params as $value) {
            $key = $value->getName();
            $position = $value->getPosition();
            $typeName = null;
            if ($value->hasType()) {
                $typeName = $value->getType()->getName();
                $this->_after_analysis_hasType($typeName);
                // dd($typeName);
            }
            $this->paramNames[$key] = [
                'position' => $position,
                'typeName' => $typeName
            ];
        }

        $this->_after_analysis();
    }

    /**
     * 改变重载传入参数
     * @param $args
     */
    protected function _before_invokeArgs(&$args){
    }

    /**
     * 构造后钩子
     */
    protected function _after_construct(){
    }

    /**
     * 存在参数类型 钩子
     */
    protected function _after_analysis_hasType($typeName){
    }
    protected function _after_analysis(){
    }

    function invokeArgs($object, array $args = []) {

        // dd($args);
        // var_dump($this->paramNames);die;
        if(is_string($object)){
            $object = new $object;
        }
        $this->_before_invokeArgs($args);
        parent::invokeArgs($object, $args);
    }

    /**
     * @return array
     */
    public function getParamNames() {
        return $this->paramNames;
    }


    /*
        ReflectionParameter :: getName - 获取参数名称
        ReflectionParameter :: getPosition - 获取参数位置
        ReflectionParameter :: getType - 获取参数的类型
        ReflectionParameter :: hasType - 检查参数是否具有类型

        dd($reflection->getConstructor()); // 获取 定义 参数名
        dd($reflection->getParameters()[0]->gettype()); // 获取 定义 参数名
        dd($reflection->getDeclaringClass () ); // 获取 必填参数 个数
        dd($reflection->getNumberOfRequiredParameters() ); // 获取 必填参数 个数
        dd($reflection->getNumberOfParameters()); // 获取定义 参数 个数

        $reflectionClass = new \ReflectionClass($controller);
        dd($reflectionClass->getMethod($ac)->getParameters()[0]->getName()); // 获取-> 类-> 方法-> 第一个参数-> 名字
        dd($reflectionClass->getMethod($ac)->getParameters()[0]->getType()->getName()); // 获取-> 类-> 方法-> 第一个参数-> 类型-> 类型名

        $a =  get_class_methods($controller); // 获取 类方法
        dd($a);


    */

}