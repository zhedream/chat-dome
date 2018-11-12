<?php




$arr = array(['黑','白','红','绿'],['3','4','5'],['大','小']);

	for ($i=0; $i < count($arr) ; $i++) { 
		$point[$i] = count($arr[$i]);
	}

	// exit;

echo "\n{";

for ($i=0; $i <count($arr[0]) ; $i++) 

	for ($j=0; $j <count($arr[1]) ; $j++) 

		for ($k=0; $k <count($arr[2]) ; $k++) { 

			printf("<%s,%s,%s> ",$arr[0][$i],$arr[1][$j],$arr[2][$k]); 
		
		}
		

echo "\n}";

//静态
	// $one = ;//指针 1：指向 集合
	// $two = ;//指针 2：指向 元素

	


// function go(){
// 	static $point = [ 
// 					  [0,2],//集合指针
// 					  [0,3],//元素指针
// 					  [0,2],
// 					  [0,1]
// 					]



// }

?>