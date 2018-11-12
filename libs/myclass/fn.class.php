<?php

final class fn{




// fn.获取字符串后缀():string. 
static function str_LastType($str,$isPoint=false)
{
    if($isPoint)
    	return substr($str,strrpos($str,"."));
    return substr($str,strrpos($str,".")+1);
}
// fn.增强版的path_info(). 
static function path_info($filepath)   
{
    $path_parts = array();   
    $path_parts ['dirname'] = rtrim(substr($filepath, 0, strrpos($filepath, '/')),"/")."/";   
    $path_parts ['basename'] = ltrim(substr($filepath, strrpos($filepath, '/')),"/");   
    $path_parts ['extension'] = substr(strrchr($filepath, '.'), 1);   
	$path_parts ['filename'] = ltrim(substr($path_parts ['basename'], 0, strrpos($path_parts ['basename'], '.')),"/");   
    return $path_parts;
}

// fn.判断奇数偶数():bool. 1.number,
static function ifoushu($a){
	/*严格格式*/
	"use strict"; 
    //	var a =3;

	if($a%2===0){
        
    //		alert("为偶数");
		return 1;
	}
	else{
    //		alert("为奇数");
		return 0;
		
	}
	
	function a(){
		
	}
}

// fn.是否为类水仙花():bool. 1.number/string,
static function if_flower($str){
	/*严格格式*/
	"use strict"; 
	$sum=0;// 数值型 ：水仙花（花数，如 玫瑰花数） 每位数 的 相应 次方 的和  再判断 水仙数 str  是否 成立  ;
    $str=(string)($str);//不管传入参数 是字符 还是数值  都转换成 字符型，再进行 运算
    $len=strlen($str);
    for($k=0;$k<$len;$k++){
        $sum+=pow($str[$k],$len); 
    }
    if($sum==$str){
        return 1;// 是 花数 返回 1
    }
    else{
        return 0;//不是 花 数 返回 0
    }
}

// fn.交换变量(). 1.&变量a,2.&变量b,
static function swap(&$a , &$b){
	$tem;
	$tem=$a;
	$a=$b;
	$b=$tem;
}
// fn.返回时间():date.
static function microtime_float(){
	$time=microtime();
	list($msec,$sec)=explode(" ",$time);
	return $sec+$msec;
}

// fn.字符串中查找 所有 子字符串():array. 1.字符串str,2.子字符串str,3.是否大小写bool,
static function strAllops($str/**/,$str1,$boo=true){
    //再字符串中中查找 所有 字符串，返回数组 [0]个数,之后 索引存放位置
		//$str=explode($str);
		//echo $str;
	// boo  是否大小写  //  待完成
		$count=[0];
	for($i=0,$j=1;$i<strlen($str);$i++){
		if($str[$i]==$str1){
			$count[0]++;
			$count[$j++]=$i;//     //用 each()???  拓展
		}	
	}
	return $count;
}
// fn.判断是否质数():bool. 1.number,
static function isPrimeNumber($arr,$boo=true){
    // 1不是质数,也不是合数.
	$arr=(int)$arr;
	if($boo){
		if($arr===1)
			return 0;
		for($i=2;$i<$arr;$i++)
			if($arr%$i===0)
				return 0;
		return 1;
	}else{
		if($arr===1)
			return 0;
		for($i=2;$i<$arr;$i++)
			if($arr%$i===0)
				return 1;
		return 0;
	}
}

	/**
	 * fn.反转字符串编码(gbk<->UTF-8). 
	 * 1.字符串,
	 * 2.是否取地址
	 */
static function auto_iconv(&$str,$isAuto=true){

	$encode = mb_detect_encoding($str, array("UTF-8","GBK","GB2312","ASCII","BIG5"));
	if($isAuto){
		if ($encode=="CP936"){
			$str = iconv("GBK","UTF-8",$str);
		}else {
			$str = iconv("UTF-8","GBK",$str);
		}
		return $str;
	}else {
		if ($encode=="CP936"){
			return iconv("GBK","UTF-8",$str);
		}else {
			return iconv("UTF-8","GBK",$str);
		}
	}
}

static function substr_ab($str,$a=0,$b=0){
    $len = strlen($str);
    $b = $b>0? $b :$len;
    $str2 = "";
    for($i=$a;$i<$b && $i<strlen($str);$i++)
        $str2 .= $str[$i];

    // echo $str2;
    return $str2;
}








// fn结束
}
?>