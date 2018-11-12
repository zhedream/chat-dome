<?php


$arr = [
    'a'=>1,
    'b'=>2,
    'c'=>3,
    'd'=>4,
];
    /**
     * // 迭代数组   
     * 1. 迭代对象  
     * 2. 回调函数 (每一次的return结果 会作为下一次迭代的参数传入,每次从 $arr 传入一个值 并 偏移指针) : 回调函数的最后一次 return 会作为 最终的返回值   
     * 3. 其他参数
     */
$arr2 = array_reduce($arr,function($a,$b){
    static $le=0;
    static $count = 1;
    echo $count++;
    if($le==0){
        $le++;
        return $b;
    }
    return $a.'-'.$b;
});

function a(){

    $arr = [
        'a'=>1,
        'b'=>2,
        'c'=>3,
        'd'=>4,
    ];
    $tem = 2;
    $arr2 = array_reduce($arr,function($a,$b){
        return $a+$b;
    },$tem);

    echo $arr2;

}



var_dump($arr2);