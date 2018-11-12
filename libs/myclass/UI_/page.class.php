<!-- <link rel="stylesheet" href=""> -->
<style>



    
</style>
<?php


class page{

    public static function makePages($pageCount,$page,$class=''){
            //  不 GET page 在 传第的参数 page  已过滤 非法 数值
         $get_=$_GET;
         if(isset($get_['page']))
            unset($get_['page']);

        $GetStrNp = "?";
        foreach ($get_ as $key => $value) {
            $GetStrNp.=$key.'='.$value.'&';
        }
        // $GetStrNp .= "&";
        
        // 配置
            $pmax=5;// 最少显示页数 最好单数页 - 1 左右/2  总页数 $pageCount
            $leftp=2; // 当前页 左边显示 页数
            $rightp=2;// 当前页 右边显示 页数
            $p1=$page-$leftp>0? $page-$leftp: 1;
            $p2=$page+  $rightp<$pageCount ? $page+  $rightp : $pageCount;
            // echo $pmax."<br>";
            // echo $p2-$p1."<br>";
            // echo $pageCount-$page."<br>";
            while($p2-$page<$pageCount-$page && $p2-$p1<$pmax-1 && $p2<=$pageCount){
                    $p2++;
            }
            while($page-$p1>0 && $p2-$p1<$pmax-1 && $p1>1){
                    $p1--;
            }

            // echo $_SESSION['']
        $aStr = "<div class=\"pageSelect\">";
        $aStr .= "<span>共 <b>{$GLOBALS['infoCount']}</b> 条 每页 <b>{$GLOBALS['config']['pageSize']['admin_pagesize']} </b>条   {$page}/{$pageCount}</span>";
        $aStr .= "<div class=\"pageWrap\">";


        $aStr .= " <a class=\"\" href=".$GetStrNp."page=1>首页</a>";
        $aStr .= " <a class=\"pagePre\" href=".$GetStrNp."page=".(($page-1)? $page-1:1)."><i class=\"ico-pre\">&nbsp;</i></a>";
        for($i=$p1;$i<=$p2;$i++){
            if($i==$page)
                $aStr .= " <a class=\"pagenumb cur\" href=".$GetStrNp."page={$i}".">$i</a>";
            else
                $aStr .= " <a class=\"pagenumb\" href=".$GetStrNp."page={$i}".">$i</a>";
            
        }
        $aStr .= " <a class=\"pagenext\" href=".$GetStrNp."page=".(($page+1)<$pageCount?$page+1:$pageCount)."><i class=\"ico-next\">&nbsp;</i></a>";
        $aStr .= " <a class=\"\" href=".$GetStrNp."page=".$pageCount.">末页</a>";
        $aStr .= " <input class=\"\" type=\"text\">";
        $aStr .= " <input class=\"\" type=\"button\" value=\"转到\" onclick=\"\">";

            $aStr .="</div>";
        $aStr .="</div>";

        // echo "总页数".$pageCount."<br>";
        // echo "当前页".$page."<br>";

        return $aStr;

    }




}



?>

<!-- <a href="?">首页</a>
<a href="?">上一页</a>

<a href="javascript:;">...</a>
<a href="?">3</a>
<a href="?">4</a>
<a href="?">5</a>
<a href="javascript:;">...</a>

<a href="?">下一页</a>
<a href="?">末页</a>
<input type="text" onclick=""> -->