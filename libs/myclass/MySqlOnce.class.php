<?php


class MySqlOnce{
    // const PP = 1.11; // 类常量
    private static $_obj = null;
    private static $pdo = null;
    private function __construct(){}
    private function __clone(){}

    public static function GetObj($host,$user,$pwd,$dbname,$charset="utf8"){

        if(!self::$pdo instanceof self){
            // if(self::$_obj==null)
            self::$_obj = new self;
            try{
            $dsn = "mysql:host=".$host.";dbname=".$dbname;
            //echo $dsn;
            self::$pdo = new PDO($dsn,$user,$pwd);

                // 抛出 异常
            // self::$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
                // 默认 提取模式
            self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC); //  PDO::FETCH_ASSOC / PDO::FETCH_NUM

                $num = self::$pdo->exec("set names {$charset}");
                //echo $num.'--------------------------';
                // echo "{$dbname}连接成功<br>";
            }catch(PDOException $e){
                echo "{$dbname}数据库连接失败！".$e->getMessage();
            }
           
        }

        return self::$_obj;
    }
    public static function getpdo(){
        if(self::$_obj!=null)
            return self::$pdo;
    }

    function beginTransaction(){

        if(self::$_obj!=null)
            self::$pdo->beginTransaction();
    }


    function exec($sql,$data=[]){
            // 绑定 sql
        $stmt = self::$pdo->prepare($sql);
            // 绑定数据
        return $stmt->execute($data);
        
    }

    function findAll($sql,$data=[]){

        $stmt = self::$pdo->prepare($sql);
            // 绑定数据 并 执行
        if($stmt->execute($data)){
            // $stmt->setFetchMode(PDO::FETCH_ASSOC);//PDO::FETCH_ASSOC
            $arr = $stmt->fetchAll();
            return $arr;
        }else {
            return false;
        }
    }

    function findOne($sql,$data=[]){
        $stmt = self::$pdo->prepare($sql);
            // 绑定数据 并 执行
        if($stmt->execute($data)){
            $stmt->setFetchMode(PDO::FETCH_ASSOC);//PDO::FETCH_ASSOC
            $arr = $stmt->fetch();
            return $arr;
        }else {
            return false;
        }

    }

    function findtest($sql,$data){
        $sql = "select * from baijia_users where id > :id limit 3";
        // $a =2;
        $data=[];
        $data[":id"]= "1";
        // $data[":lm"]= 2;
        $stmt = self::$pdo->prepare($sql);
        var_dump($stmt,$data);
        $num = $stmt->execute($data);
        $arr = $stmt->fetchAll();
        var_dump($num,$arr);

    }

    function __destruct(){
        self::$pdo = null;
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
