<?php

$data = array(['黑','白','红','绿'],['1','2','3'],['大','中','小'],['A','B','C'],['x','y','z']);

/*   -  1  - 1 -  1    [
    2   2   2           [1,4],[1,3],[1,2]
    3   3           }
    4    
    
    1   1   1
    1   1   2
    1   2   1 
    
*/
$data = null;
$data = array(['A','B'],['1','2']);

$data = go($data);
var_dump($data);

function go($data){
        // 初始化 指针
    for ($i=0; $i <count($data) ; $i++)
        $point[] = [0,count($data[$i])-1];

    $tot = count($point);
    while ($point[0][0] <= $point[0][1]) {
            // 输出
            echo "第".(($i++)-1)."个结果：-----------";

        $tem = null;
        for ($j=0; $j <count($point) ; $j++) {
           
            echo $data[  $j   ][   $point[$j][0]  ];
            $tem[] =$data[  $j   ][   $point[$j][0]  ];
        }
        $result[]=$tem;

        echo "<br>";

        $point[$tot-1][0]++;
        
        for ($k=0; $k < $tot ; $k++)
            if($point[$k][0] > $point[$k][1]){
                if($k == $tot-1)
                    $point[$k][0] = 0;
                else
                    $point[$k][0] = 0;
                $point[$k-1][0] ++;

                --$k;// 重新检查
            }
    }

   return($result);
}