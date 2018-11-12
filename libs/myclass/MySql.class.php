<?php





class MySql{

    var $host; //主机名
    var $port; // 端口
    var $dbname;//
    var $user;// 
    var $pwd;
    var $pdo;


    function __construct($host,$user,$pwd,$dbname,$charset="utf8"){

        try{
            $dsn = "mysql:host=".$host.";dbname=".$dbname;
            //echo $dsn;
            $this->pdo = new PDO($dsn,$user,$pwd);

            // 抛出 异常
		// $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
			// 默认 提取模式
		// $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC); //PDO::FETCH_NUM

            $num = $this->pdo->exec("set names {$charset}");
            //echo $num.'--------------------------';
            echo "连接成功";
        }catch(PDOException $e){
            echo "数据库连接失败！".$e->getMessage();
        }


    }


    function exec($sql,$data=[]){
            // 绑定 sql
        $stmt = $this->pdo->prepare($sql);
            // 绑定数据
        return $stmt->execute($data);
        


    }

    function findAll($sql,$data=[]){

        $stmt = $this->pdo->prepare($sql);
            // 绑定数据 并 执行
        if($stmt->execute($data)){
            $stmt->setFetchMode(PDO::FETCH_ASSOC);//PDO::FETCH_ASSOC
            $arr = $stmt->fetchAll();
            return $arr;
        }else {
            return false;
        }

        




    }

    function findFirstColumn($sql,$data=[]){
        $stmt = $this->pdo->prepare($sql);
            // 绑定数据 并 执行
        if($stmt->execute($data)){
            $stmt->setFetchMode(PDO::FETCH_ASSOC);//PDO::FETCH_ASSOC
            $arr = $stmt->fetch();
            return $arr;
        }else {
            return false;
        }

        
    }

    function __destruct(){
        $this->pdo = null;
    }

}










?>

<?php
        /*      
                // 原始
            $num = $pdo->exec($sql);// 发送  指令
            if($num!=false)
            echo "更新数据到表{$tb}成功，影响{$num}"."最后主键ID为".$pdo->lastInsertId();
        */


        /*
                // 原始
            $data = $pdo->query($sql);
            $info = array();	
            if($data!=false)
                $info = $data->fetch();

        */
?>
