<?php

$data = array(['黑','白'],['4G','8G','16G']);

// $data = go($data);
// var_dump($data);

function go($data){
        // 初始化 指针
    for ($i=0; $i <count($data) ; $i++)
        $point[] = [0,count($data[$i])-1];

    $tot = count($point);
    while ($point[0][0] <= $point[0][1]) {

        // $tem = null;
        for ($j=0; $j <count($point) ; $j++) 
            $tem[] = $data[  $j   ][   $point[$j][0]  ];

        $result[]=$tem;
        $point[$tot-1][0]++;

        for ($k=0; $k < $tot ; $k++)
            if($point[$k][0] > $point[$k][1]){
                if($k == $tot-1)
                    $point[$k][0] = 0;
                else
                    $point[$k][0] = 0;
                $point[$k-1][0] ++;

                $k=0;// 重新检查
            }
    }

   return($result);
}

/**
 * 笛卡尔积
 * 1.传入 二维数组 $data = array(['A', 'B', 'C'], ['1', '2'], ['黑', '白', '红']);
 * 2.返回 笛卡尔 积数组
 */

var_dump( toDescartesProduct($data));
// var_dump( go($data));

function toDescartesProduct(array $data)
{
    // 初始化 指针
    for ($i = 0; $i < count($data); $i++) {
        $point[] = [0, count($data[$i]) - 1];
    }

    $tot = count($point);
    while ($point[0][0] <= $point[0][1]) {

        $tem = null;
        for ($j = 0; $j < count($point); $j++) {
            $tem[] = $data[$j][$point[$j][0]];
        }

        $result[] = $tem;
        $point[$tot - 1][0]++;

        for ($k = 0; $k < $tot; $k++) {
            if ($point[$k][0] > $point[$k][1]) {
                if ($k == $tot - 1) {
                    $point[$k][0] = 0;
                } else {
                    $point[$k][0] = 0;
                }
                
                $point[$k - 1][0]++;

                $k = 0; // 重新检查
            }
        }

    }

    return ($result);
}