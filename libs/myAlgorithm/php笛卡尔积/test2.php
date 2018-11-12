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


$arr1 = [[0,3],[0,2],[0,1]];
    // 初始化 指针
for ($i=0; $i <count($data) ; $i++) { 
    $point[] = [0,count($data[$i])-1];
}

var_dump($point);

b($point);

function b($point){
    
    global $data;

    $tot = count($point);
    echo "集合个数{$tot}<br>";
    // var_dump($point);
    echo "<br>";
    // echo $tot;

   for ($i=0 ;  $point[0][0] <= $point[0][1] ;    ) {
        
        echo "当前指针".$point[0][0]."::".$point[1][0]."::".$point[2][0]."<br>";
            // 输出
            echo "第".($i++)."个结果：--------------------";
        for ($j=0; $j <count($point) ; $j++) {
           
            echo $data[  $j   ][   $point[$j][0]  ];
            $tem[] =$data[  $j   ][   0  ];
        }
        echo "<br>";

        $point[$tot-1][0]++;
        
        echo "开始检查<br>";
        
        for ($k=0; $k < $tot ; $k++) {

            if($point[$k][0] > $point[$k][1]){
                if($k == $tot-1)
                    $point[$k][0] = 0;
                else
                    $point[$k][0] = 0;
                $point[$k-1][0] ++;

                $k=0;// 重新检查
                echo "偏移<br>";
                echo "偏移后".$point[0][0]."::".$point[1][0]."::".$point[2][0]."<br>";
            }

        }

   }

}






// a([2,2],[[0,3],[0,2],[0,2]]);
// dd($aa);
function a($p,$point){

    
    
    echo "当前元素指针".($p[0])."::";echo $point[0][0].",";echo $point[1][0].",";echo $point[2][0].",<br>";
    static $set =[];
    global $data;
    $arr = $point;


    
    if($arr[   $p[0]  ][    0   ] >  $arr[   $p[0]  ][    1   ] ) {echo '<br><<元素指针边界<br>';return;}
    // echo ($p)."::";echo $arr[0].",";echo $arr[1].",";echo $arr[2].",<br>";

            $tem = null; 
        for ($i=0; $i <count($arr) ; $i++) {
           
            echo $data[  $i   ][   $arr[$i][0]  ];
            $tem[] =$data[  $i   ][   0  ];
        }
        echo "<br>";
            $set[]=$tem;

        

        // 偏移 当前集合 元素指针

        ++$arr[   $p[0]  ][    0   ];
        // if(++$arr[   $p[0]  ][    0   ] >  $arr[   $p[0]  ][    1   ] ) {echo '<br><<元素指针边界<br>';return;}
        
        echo "偏移指针-------------------->>".($p[0])."::";echo $arr[0][0].",";echo $arr[1][0].",";echo $arr[2][0].",<br>";

        a($p,$arr); //执行

        
        echo "<< <br>";
        $arr[   $p[0]  ][    0   ] = 0; //$arr[   $p[0]  ][    1   ]
        echo "重置指针-------------------->>".($p[0])."::";echo $arr[0][0].",";echo $arr[1][0].",";echo $arr[2][0].",<br>";


        // 偏移 集合指针
        --$p[0];
        echo "集合偏移-------------------->>".($p[0])."::";echo $arr[0][0].",";echo $arr[1][0].",";echo $arr[2][0].",<br>";

        a($p,$arr); //执行


        

        
        // exit;





}






exit;



$arr = [3,2,1];


// go(count($arr)-1,$arr);

function go($p,$arr){
    static $sum=0;
    $p1 = $p;
    $arr1 = $arr;
    echo ($p+1)."::";echo $arr[0].",";echo $arr[1].",";echo $arr[2].",<br>";
    
    if($p<0) 
        return 0;
    else

    
    if($arr[$p]<0) return 0;

    if($p==-1||$arr[$p]==-1) return 0;

    echo "输出：".$arr[0].",";echo $arr[1].",";echo $arr[2].",<br>"; // 输出
    $sum++;
    echo "<br>";
    
    echo ">>减<br>";
    --$arr1[$p1];
    go($p1,$arr1);
    echo "<<减<br>";


    echo $arr1[0].",";echo $arr1[1].",";echo $arr1[2].",<br>";

    echo ">>偏移<br>";
    go($p1-1,$arr1);
    echo "<<偏移<br>";

    echo "<br>sun:".$sum."<br>";
}



//

Route::get('/test',function(){
    
    $arr = array(['黑','白','红','绿'],['3','4','5'],['大','小']);
    
    // for ($i=0; $i < count($arr) ; $i++) { 
    //     $point[$i] = count($arr[$i]);
    //     // $point[] = [$i,count($arr[$i])-1];
    // }
        // dd($arr);

        $point []= [0,2];
    for ($i=0; $i < count($arr) ; $i++) { 
        // $point[$i] = count($arr[$i]);
        $point[] = [0,count($arr[$i])-1];

    }

    /**
     $point = [ 
					  [0,2],//集合指针
					  [0,3],//元素指针
					  [0,2],
					  [0,1]
					]
     */
    // dd($point);



    // // for ($i=0; $i < ; $i++) { 

    // //     echo $arr[  $point[0][0]   ][   $point[$point[0][0]][0]   ];
    // // }



    // // $point[$i];

	// exit;

        printf("集合a：<br>");
        for ($i=0;$i<count($arr[0]);$i++) printf("%s\t",$arr[0][$i]);
        echo "<br>";
        printf("集合b：<br>");
        for ($i=0;$i<count($arr[1]);$i++) printf("%s\t",$arr[1][$i]);
        echo "<br>";
        printf("集合c：<br>");
        for ($i=0;$i<count($arr[2]);$i++) printf("%s\t",$arr[2][$i]);
        echo "<br><br>";

    echo "\n{";
    for ($i=0; $i <count($arr[0]) ; $i++) 
    
        for ($j=0; $j <count($arr[1]) ; $j++) 
    
            for ($k=0; $k <count($arr[2]) ; $k++) {
    
                printf("<%s,%s,%s> ,<br>",$arr[0][$i],$arr[1][$j],$arr[2][$k]); 
            
            }
    echo "\n}";


});




?>