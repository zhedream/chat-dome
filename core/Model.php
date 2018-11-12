<?php

namespace core;

use \PDO;
use core\DB;
use core\RD;

Model::ModelRun();

class Model {

    private static $pdo = null;
    private static $redis = null;

    protected $table = null;
    protected $fillable = null;
    protected $fillData; // 填充的数据

    protected $select = "SELECT * ";
    protected $from = null;

    protected $where = null;
    protected $whereKeys = null;
    protected $whereVals = null;
    protected $join = null;
    protected $leftJoin = null;
    protected $rightJoin = null;
    protected $groupBy = null;
    protected $having = null;
    protected $orderBy = null;
    protected $limit = null;

    protected $startGroup = null;


    function __construct() {
        self::db();
        self::rd();
        $this->table = $this->table();
    }

    /**
     * @return null
     */
    public function getFillable() {
        return $this->fillable;
    }

    /**
     * @return mixed
     */
    public function getFillData() {
        return $this->fillData;
    }

    // 钩子 函数
    protected function _before_write() {
    }

    protected function _after_write() {
    }

    protected function _before_delete() {
    }

    protected function _after_delete() {
    }

    static function ModelRun() {
        self::db();
        self::rd();
    }

    function table($option = 1) {
        if ($this->table) {
            return $this->table;
        }
        $class = get_called_class();
        // echo $class."<br>";
        // $class = end(explode('\\', $class)); // 存在异常
        $class = explode('\\', $class);
        $class = end($class);

        $last = substr($class, -1);
        if ($last == 's')
            $class = $class;
        else
            $class = $class . 's';

        $class = lcfirst($class);
        $class = preg_replace_callback('/([A-Z])+/', function ($matches) {
            return "_" . strtolower($matches[1]);
        }, $class);

        $prefix = $GLOBALS['config']['db']['prefix'];
        //拼接表名
        return $prefix . $class;

    }

    private static function db() {
        if (self::$pdo == NULL) {
            self::$pdo = DB::getDB();
        }
    }

    private static function rd() {
        if (self::$redis == NULL) {
            self::$redis = RD::getRD();
        }
    }

    static function findAll($sql, $data = []) {
        $stmt = self::$pdo->prepare($sql);
        if ($stmt->execute($data)) {
            $stmt->setFetchMode(PDO::FETCH_ASSOC);// PDO::FETCH_ASSOC  //  PDO::FETCH_NUM
            $arr = $stmt->fetchAll();
            return $arr;
        }
        return false;
    }

    static function findOne($sql, $data = []) {
        // if (self::$pdo === NULL) self::db();
        $stmt = self::$pdo->prepare($sql);
        if ($stmt->execute($data)) {
            $stmt->setFetchMode(PDO::FETCH_ASSOC);//PDO::FETCH_ASSOC
            $arr = $stmt->fetch();
            return $arr;
        }
        return false;
    }

    static function findOneFirst($sql, $data = []) {
        $stmt = self::$pdo->prepare($sql);
        if ($stmt->execute($data)) {
            $stmt->setFetchMode(PDO::FETCH_NUM);//PDO::FETCH_ASSOC  PDO::FETCH_NUM
            $arr = $stmt->fetch();
            // dd($arr);
            return $arr[0];
        }
        return false;
    }

    /**
     * 返回 所有数据的第一个字段  一维数组
     */
    static function findOneFirsts($sql, $data = []) {
        $stmt = self::$pdo->prepare($sql);
        if ($stmt->execute($data)) {
            $stmt->setFetchMode(PDO::FETCH_NUM);//PDO::FETCH_ASSOC
            $arr = $stmt->fetchAll();
            $data = [];
            foreach ($arr as $key => $value) {
                $data[] = $value[0];
            }
            return $data;
        }
        return false;
    }

    static function exec($sql, $data = []) {
        // dd($sql);
        $stmt = self::$pdo->prepare($sql);
        // return @$stmt->execute($data);
        return $stmt->execute($data);
    }

    function insert() {
        if ($this->fillData) {
            // var_dump($this->fillData);die;
            return $this->exec_insert($this->fillData);
        } else
            throwE('请先使用 fill 填充数据', 'insert');
    }

    function update($condition = []) {
        // 默认 以 where id  可以传入 条件
        // var_dump($this->where);die;
        // var_dump($this->whereKeys);die;
        return $this->exec_update($this->fillData, $this->where);
    }

    function delete() {
        // var_dump($this->where);die;
        // var_dump($this->whereKeys);die;
        // var_dump($this->whereVals);die;
        return $this->exec_delete($this->where);
    }

    /**
     * 自动 update OR insert
     * ( 存在 $this->where  update  否则 insert )
     */
    function save() {
    }

    /**
     * 自动填充数据
     * 1. 数据
     * 2. 填充模式
     */
    function fill($data, $must = false) {
        // dd($data);
        // var_dump($data);
        if ($this->fillable) {
            $tem = [];
            if ($must) {

                foreach ($this->fillable as $key)
                    $tem[$key] = $data[$key];

            } else {

                foreach ($data as $key => $val)
                    if (in_array($key, $this->fillable))
                        $tem[$key] = $val;
            }
            // dd($tem);
            $this->fillData = $tem;
        } else {
            throwE(get_called_class() . '的$fillable 不能为空', 'fill');
        }
        return $this;

    }

    /**
     * update 语句
     * 0. 表名 = 类名+s;
     * 1. 更改的字段 与 值 ['name'=>'名字']
     * 2. 更改的条件 与 值 ['id'=>7]
     */
    function exec_update(array $data, $where) {
        // \ob_clean();
        // \ob_clean();
        $keys = array_keys($data); // 所有设置的 字段
        $vals = array_values($data);// 所有设置的 字段值

        //dd($condition);
        $set = '';
        foreach ($keys as $key => $value) {
            if ($value == end($keys))
                $set .= "`$value`=? ";
            else
                $set .= "`$value`=?, ";

            // echo $value."->";
        }

        // dd($where);
        $data = [];
        if (!$this->whereVals) {
            $this->whereVals = [];
        }
        foreach ($vals as $key => $value) {
            // array_unshift($data,$value);
            $data[] = $value;
        }
        $this->whereVals = array_merge($data, $this->whereVals);


        $table = $this->table();
        // dd($table);
        $table = "`$table`";
        $sql = "UPDATE {$table} SET {$set} {$where}";
        //    var_dump($sql, $this->whereVals);die;
        return self::exec($sql, $this->whereVals);
    }

    function exec_update_old(array $data, array $condition) {
        // \ob_clean();
        $keys = array_keys($data); // 所有设置的 字段
        $vals = array_values($data);// 所有设置的 字段值
        // var_dump($keys,$vals,end($keys));die;
        $wherekeys = array_keys($condition); // 条件字段
        $wherevals = array_values($condition); // 条件值
        //dd($condition);
        $set = '';
        foreach ($keys as $key => $value) {
            if ($value == end($keys))
                $set .= "`$value`=? ";
            else
                $set .= "`$value`=?, ";
            // echo $value."->";
        }
        // die;
        // dd($set);
        $where = '';
        foreach ($wherekeys as $key => $value) {
            if ($key == end($wherekeys))
                $where .= "`$value`=? ";
            else
                $where .= "`$value`=?, ";
        }
        // dd($where);
        $data = [];

        foreach ($vals as $key => $value) {
            // array_unshift($data,$value);
            $data[] = $value;
        }

        foreach ($wherevals as $key => $value) {
            // array_unshift($data,$value);
            $data[] = $value;
        }
        $table = $this->table();
        // dd($table);
        $table = "`$table`";
        $sql = "UPDATE {$table} SET {$set} WHERE {$where}";
        dd($sql);
        return self::exec($sql, $data);
    }

    /**
     * insert 语句
     * 0. 表名 = 类名+s;
     * 1. 更改的字段 与 值 ['name'=>'名字']
     */
    function exec_insert(array $data) {

        $keys = array_keys($data); // 插入设置的 字段
        $vals = array_values($data);// 插入设置的 字段值
        // var_dump($keys,$vals);die;
        $fillkeys = '';
        // $fillkeys = implode(',',$keys);
        // dd($fillkeys);
        foreach ($keys as $key => $value) {
            if ($value == end($keys))
                $fillkeys .= "`$value` ";
            else
                $fillkeys .= "`$value`, ";
            // echo $value."->";
        }

        $fillarea = '';
        foreach ($keys as $key => $value) {
            if ($value == end($keys))
                $fillarea .= "? ";
            else
                $fillarea .= "?, ";
        }

        $data = [];
        foreach ($vals as $key => $value) {
            // array_unshift($data,$value);
            $data[] = $value;
        }

        $table = $this->table();
        $table = "`$table`";
        $sql = "INSERT INTO {$table} ({$fillkeys}) VALUES ({$fillarea})";
        // var_dump($sql,$data);die;
        // dd($sql);
        return self::exec($sql, $data);
    }

    function exec_delete($where) {

        // $wherekeys = array_keys($condition); // 条件字段
        // $wherevals = array_values($condition); // 条件值
        // // dd($condition);
        // $where = '';
        // foreach ($wherekeys as $key => $value) {
        //     if ($key == end($wherekeys))
        //         $where .= "`$value`=? ";
        //     else
        //         $where .= "`$value`=?, ";
        // }
        // // dd($where);
        // $data = [];
        // foreach ($wherevals as $key => $value) {
        //     // array_unshift($data,$value);
        //     $data[] = $value;
        // }

        $table = $this->table();
        // dd($table);
        $table = "`$table`";
        $sql = "DELETE FROM {$table} {$where}";
        return self::exec($sql, $this->whereVals);
    }

    function exec_select() {
    }

    final function select() {
        $args = func_get_args();
        $num = func_num_args();
        if ($num == 0) {
            $this->select = "SELECT * ";
            return $this;
        }
        if (is_array($args[0])) {
            dd($args);
            return call_user_func_array([__NAMESPACE__ . '\Model', "selectArr"], $args);
        } else {

            // dd($args);
            return call_user_func_array([__NAMESPACE__ . '\Model', "selects"], $args);
        }
        // dd($args);
    }

    function selects() {
        $data = func_get_args();
        $str = \implode(',', $data);
        $this->select = "SELECT $str ";
        return $this;
    }

    // 待编写
    function selectArr($data) {
        return $this;
    }

    final function from(array $data = []) {
        if (!$this->from)
            $this->from = " FROM " . "`" . $this->table() . "` ";

        return $this;
    }

    // --------------   WHERE 
    final function where() {

        $args = func_get_args();
        // dd($args);
        $num = func_num_args();
        if ($num == 1) {
            // whereID()
            if (is_int($args[0]) or is_string($args[0]))
                return call_user_func_array([__NAMESPACE__ . '\Model', "whereId"], $args);

            if (is_array($args[0]))
                return call_user_func_array([__NAMESPACE__ . '\Model', "whereArr"], $args);
        }
        return call_user_func_array([__NAMESPACE__ . '\Model', "where" . $num], $args);
    }

    final function whereId($id) {
        // dd($id);
        $where = " AND `id`=? ";
        // $where = " AND `id`=" . $id;
        // 储存 条件值
        if (!$this->whereVals) {

            $this->whereVals = [];
            $this->whereKeys = [];
        }
        $this->whereVals[] = $id;
        $this->whereKeys[] = 'id';

        if (!$this->where) {
            $this->where = "WHERE 1 " . $where;
        } else {
            $this->where .= $where;
        }

        return $this;
    }

    /**
     * 待更新
     * for 数组 的长度 再进行 拼接 语句
     */
    final function whereArr($condition) {
        $wherekeys = array_keys($condition); // 条件字段
        $wherevals = array_values($condition); // 条件值
        // dd($condition);
        $where = '';
        // 第一次 where 为空
        if (!$this->where) {

            foreach ($condition as $key => $value) {
                if ($key == end($wherekeys))
                    $where .= " AND `$key`=? ";
                // $where .= " AND `$key`=$value ";
                else
                    $where .= " AND `$key`=? ";
                // $where .= " AND `$key`=$value ";
            }
            $this->where = "WHERE 1 " . $where;

        } else {

            foreach ($condition as $key => $value) {
                if ($key == end($wherekeys))
                    $where .= " AND `$key`=? ";
                // $where .= " AND `$key`=$value ";
                else
                    $where .= " AND `$key`=? ";
                // $where .= " AND `$key`=$value ";
            }

            $this->where .= $where;

        }

        // 储存 条件值
        if (!$this->whereVals) {
            $this->whereVals = [];
        }
        foreach ($condition as $key => $value) {
            $this->whereVals[] = $value;
        }
        return $this;
    }

    final function where2($key, $val) {

        $where = " AND `$key`=? ";
        // $where = " AND `$key`=$val ";

        // 储存 条件值
        if (!$this->whereVals)
            $this->whereVals = [];
        $this->whereVals[] = $val;

        if (!$this->where) {
            $this->where = "WHERE 1 " . $where;
        } else {
            $this->where .= $where;
        }

        return $this;
    }

    final function where3($key, $sign, $val) {

        $where = " AND `$key` $sign ? ";
        // $where = " AND `$key` $sign $val ";
        // 储存 条件值
        if (!$this->whereVals)
            $this->whereVals = [];
        $this->whereVals[] = $val; // add val

        if (!$this->where) {
            $this->where = "WHERE 1 " . $where;
        } else {
            $this->where .= $where;
        }

        return $this;
    }

    // -------------  OR WHERE   
    final function orWhere() {

        $args = func_get_args();
        // dd($args);
        $num = func_num_args();
        return call_user_func_array([__NAMESPACE__ . '\Model', "orWhere" . $num], $args);

    }

    function orWhere1($condition) {

        $wherekeys = array_keys($condition); // 条件字段
        $wherevals = array_values($condition); // 条件值
        // dd($condition);
        $where = $this->where;
        foreach ($condition as $key => $value) {
            if ($key == end($wherekeys))
                $where .= "OR `$key`=? ";
            // $where .= "OR `$key`=$value ";
            else
                $where .= "OR `$key`=? ";
            // $where .= "OR `$key`=$value ";
        }

        // 储存 条件值
        if (!$this->whereVals) {
            $this->whereVals = [];
        }
        foreach ($condition as $key => $value) {
            $this->whereVals[] = $value;
        }

        $this->where = $where;
        // dd($where);
        return $this;
    }

    function orWhere2($key, $val) {

        $where = " OR `$key`=? ";
        // $where = " OR `$key`=$val ";
        // 储存 条件值
        if (!$this->whereVals)
            $this->whereVals = [];
        $this->whereVals[] = $val;

        if (!$this->where) {
            $this->where = "WHERE 1 " . $where;
        } else {
            $this->where .= $where;
        }

        return $this;
    }

    function orWhere3($key, $sign, $val) {
        $where = " OR `$key` $sign ? ";
        // $where = " OR `$key` $sign $val ";
        // 储存 条件值
        if (!$this->whereVals)
            $this->whereVals = [];
        $this->whereVals[] = $val; // add val

        if (!$this->where) {
            $this->where = "WHERE 1 " . $where;
        } else {
            $this->where .= $where;
        }

        return $this;
    }

    // ---------------  WHERE IN
    final function whereIn() {

        $args = func_get_args();
        $num = func_num_args();
        // dd($args);
        if (is_array($args[1])) {
            return call_user_func_array([__NAMESPACE__ . '\Model', "whereInArr"], $args); // 数组 数据
        }
        if (is_callable($args[1])) {
            // $args[1] = 123;
            // var_export($args[1]);
            // die;
            return call_user_func_array([__NAMESPACE__ . '\Model', "whereInCall"], $args); // 回调 数据/对象
        }
        if (is_string($args[1])) {
            return call_user_func_array([__NAMESPACE__ . '\Model', "whereInSql"], $args); // 回调 数据/对象
        }

        die;
    }

    // 不进行 预处理 !!
    function whereInArr($k, $arr, $not = false) {

        $keys = array_keys($arr); // 获取 索引
        if (!$this->whereVals)
            $this->whereVals = [];

        $val = "(";
        foreach ($arr as $key => $value) {
            if ($key == end($keys))
                $val .= "$value";
            else
                $val .= "$value,";

            // $this->whereVals[] = $value; // add val
        }
        $val .= ")";

        if ($not)
            $where = " AND `$k` NOT IN $val ";
        else
            $where = " AND `$k` IN $val ";
        // $this->where .=  $where; // link sql
        if (!$this->where) {
            $this->where = "WHERE 1 " . $where;
        } else {
            $this->where .= $where;
        }

        return $this;

    }

    function whereInCall($k, $call, $not = false) {
        // var_dump($call);die;
        $model = get_called_class();
        $m = new $model;
        $call($m);
        $sql = $m->toSql();
        $data = $m->whereVals;
        // dd($sql);

        if ($not)
            $where = " AND `$k` NOT IN ($sql) ";
        else
            $where = " AND `$k` IN ($sql) ";

        // $this->where .= $where;
        if (!$this->where) {
            $this->where = "WHERE 1 " . $where;
        } else {
            $this->where .= $where;
        }

        $this->whereVals = array_merge($this->whereVals, $m->whereVals);
        // $

        // var_dump($m);
        // dd($this,false);
        // 获取 当前 查询 模型 的    sql  与 变量
        return $this;
    }

    function whereInSql($k, $sql, $not = false) {
    }

    // -------------- OR WHERE IN
    final function orWhereIn() {

        $args = func_get_args();
        $num = func_num_args();
        // dd($args);
        if (is_array($args[1])) {

            return call_user_func_array([__NAMESPACE__ . '\Model', "orWhereInArr"], $args); // 数组 数据
        }
        if (is_callable($args[1])) {

            return call_user_func_array([__NAMESPACE__ . '\Model', "orWhereInCall"], $args); // 回调 数据/对象
        }
        if (is_string($args[1])) {

            return call_user_func_array([__NAMESPACE__ . '\Model', "whereInSql"], $args); // 回调 数据/对象
        }

        die;
    }

    function orWhereInArr($k, $arr, $not = false) {

        $keys = array_keys($arr); // 获取 索引
        if (!$this->whereVals)
            $this->whereVals = [];

        $val = "(";
        foreach ($arr as $key => $value) {
            if ($key == end($keys))
                $val .= "$value";
            else
                $val .= "$value,";

            // $this->whereVals[] = $value; // add val
        }
        $val .= ")";

        if ($not)
            $where = " OR `$k` NOT IN $val ";
        else
            $where = " OR `$k` IN $val ";

        $this->where .= $where; // link sql

        return $this;

    }

    // 不使用  预处理
    function orWhereInCall($k, $call, $not = false) {
        // var_dump($call);die;
        $model = get_called_class();
        $m = new $model;
        $call($m);
        $sql = $m->toSql();
        $data = $m->whereVals;
        // dd($sql);

        if ($not)
            $where = " OR `$k` NOT IN ($sql) ";
        else
            $where = " OR `$k` IN ($sql) ";

        // $this->where .= $where;
        if (!$this->where) {
            $this->where = "WHERE 1 " . $where;
        } else {
            $this->where .= $where;
        }

        $this->whereVals = array_merge($this->whereVals, $m->whereVals);
        // $

        // var_dump($m);
        // dd($this,false);
        // 获取 当前 查询 模型 的    sql  与 变量
        return $this;
    }

    function orWhereInSql() {
    }

    // 待开发 where 即可 用
    final function like() {
    }

    final function orLike() {
    }

    // 大括号  and ()
    final function group(callable $call) {
        if ($this->where)
            $this->where .= 'AND ( 1 ';
        else
            $this->where = ' WHERE ( 1 ';

        $call($this);
        $this->where .= ') ';
    }

    // or ()
    final function orGroup(callable $call) {
        $this->where .= 'OR ( 1 ';
        $call($this);
        $this->where .= ') ';
    }


    // 连表查询
    final function leftJoin($field, $condition1, $sign, $condition2) {


        if (!$this->leftJoin) {
            $this->leftJoin = "";
            $this->leftJoin = " LEFT JOIN $field on $condition1 $sign $condition2 ";
        } else {
            $this->leftJoin .= "LEFT JOIN $field on $condition1 $sign $condition2 ";
        }


        return $this;
    }

    // 连表查询
    final function rightJoin($field, $condition1, $sign, $condition2) {

        if (!$this->rightJoin) {
            $this->rightJoin = "";
            $this->rightJoin = " RIGHT JOIN $field on $condition1 $sign $condition2 ";
        } else {
            $this->rightJoin .= "RIGHT JOIN $field on $condition1 $sign $condition2 ";
        }

        return $this;
    }

    // 连表查询
    final function join($field, $condition1, $sign, $condition2) {

        if (!$this->leftJoin) {
            $this->join = "";
            $this->join = "JOIN $field on $condition1 $sign $condition2 ";
        } else {
            $this->join .= "JOIN $field on $condition1 $sign $condition2 ";
        }

        return $this;
    }


    // 分组
    final function groupBy() {
        return $this;
    }

    final function having() {
        return $this;
    }

    final function orderBy() {
        return $this;
    }

    final function limit() {

        $args = func_get_args();
        $num = func_num_args();
        // dd($args);
        return call_user_func_array([__NAMESPACE__ . '\Model', "limit" . $num], $args);
    }

    function limit1($num) {
        $this->limit = " LIMIT $num ";
        return $this;
    }

    function limit2($num, $num2) {
        $args = func_get_args();
        $this->limit = " LIMIT $num,$num2 ";
        return $this;
    }

    function toSql($fill = false) {
        $this->from();
        $sql = $this->select
            . $this->from
            . $this->join
            . $this->leftJoin
            . $this->rightJoin
            . $this->where
            . $this->groupBy
            . $this->having
            . $this->orderBy
            . $this->limit;
        if ($fill) {
            $whereVals = $this->whereVals;
            $sql = preg_replace_callback('/\?/', function ($matches) use ($whereVals) {
                static $whereVals;
                $str = "'" . current($whereVals) . "'";
                next($whereVals);
                return $str;
                // ;
            }, $sql);
        }
        // var_dump($sql, $this->whereVals);
        // die;
        return $sql;
    }

    final function get() {
        $this->from();
        $sql = $this->select
            . $this->from
            . $this->join
            . $this->leftJoin
            . $this->rightJoin
            . $this->where
            . $this->groupBy
            . $this->having
            . $this->orderBy
            . $this->limit;
        // var_dump($sql, $this->whereVals);die;
        return $this->findAll($sql, $this->whereVals);
    }
    final function first() {
        $this->from();
        $sql = $this->select
            . $this->from
            . $this->join
            . $this->leftJoin
            . $this->rightJoin
            . $this->where
            . $this->groupBy
            . $this->having
            . $this->orderBy
            . $this->limit;
        // var_dump($sql, $this->whereVals);die;
        return $this->findOne($sql, $this->whereVals);
    }

    /**
     * @param $num // 每页数量
     * @return $data // 分页数据
     */
    final function paginate($num, $pageName = 'page') {
        //    dd($_SERVER);
        $page = $_GET['page']; // 当前页
        if ($page < 1)
            $page = 1;
        $count = $this->count();
        $PageCount = ceil($count / $num); // 总页数
        // dd($PageCount);
        $this->limit($page * $num - $num, $num);
        $result = $this->get();
        $urlParams = getUrlParams(1, ['page' => 2]);
        // dd($urlParams);
        $data['current_page'] = $page; // 当前页码
        $data['first_page_url'] = $_SERVER['PATH_INFO'] . '?' . implode("&", getUrlParams(1, ['page' => 1])); // 第一页
        $data['last_page_url'] = $_SERVER['PATH_INFO'] . '?' . implode('&', getUrlParams(1, ['page' => $PageCount])); // 最后一页
        $data['prev_page_url'] = $_SERVER['PATH_INFO'] . '?' . implode('&', getUrlParams(1, ['page' => ($page - 1) >= 1 ? ($page - 1) : 1])); // 上一页
        $data['next_page_url'] = $_SERVER['PATH_INFO'] . '?' . implode('&', getUrlParams(1, ['page' => ($page + 1) <= $PageCount ? ($page + 1) : $PageCount])); // 下一页
        $data['last_page'] = $PageCount; // 最后的页码
        $data['total'] = $count; // 总数
        $this->makePageHtml($data);
        $data['data'] = $result; // 数据
        return $data;
    }

    /**
     * 制作页码等
     */
    private function makePageHtml(&$data) {
        // dd($data);
        extract($data); // 解压变量 给模板使用
        ob_start();
        include(ROOT . 'templates/page.html');
        $str = ob_get_clean();
        $data['pageHtml'] = $str;
    }

    final function count() {
        $this->from();
        $sql = "select count(*) count "
            . $this->from
            . $this->leftJoin
            . $this->where
            . $this->groupBy
            . $this->having
            . $this->orderBy
            . $this->limit;
        // var_dump($sql,$this->whereVals);die;
        return $this->findOneFirst($sql, $this->whereVals);
    }

    final function sqlReset() {
        $this->select = "SELECT * ";
        $this->from = null;

        $this->where = null;
        $this->whereKeys = null;
        $this->whereVals = null;
        $this->leftJoin = null;
        $this->groupBy = null;
        $this->having = null;
        $this->orderBy = null;
        $this->limit = null;
    }

    /**
     * 事务 SQL   注意 InnoDB 才支持 事务
     * 1.传入 [
     * [$sql1,$data1],
     * [$sql2,$data2],
     * ...
     * ]
     */
    static function Transaction($Action) {

        self::$pdo->beginTransaction();
        foreach ($Action as $v) {
            if (!self::exec($v[0], $v[1])) {
                self::$pdo->rollBack();//事务回滚 ， 貌似  可以 不添加
                return false;
            }
        }
        self::$pdo->commit();//提交(确认)
    }

    static function TransactionCall(callable $Action) {
        self::$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_WARNING); // 报错模式 非严格
        self::$pdo->beginTransaction();

        if ($Action()) {

            self::$pdo->commit();//提交(确认)
            return true;
        }
    }

    /**
     * 开启了事务不提交 可测试 Sql 语句,不会插入 数据库
     */
    static function testSql($Action) {
        // die('AAA');
        // dd($Action);
        self::$pdo->beginTransaction();
        foreach ($Action as $v)
            // dd($v);
            if (!self::exec($v[0], $v[1]))
                return false;

        self::$pdo->rollBack();
        return true;
    }

    public function __call($name, $arr) {

        echo "该模型" . __CLASS__ . "不存在方法" . $name . lm;

    }

    /**
     * 对无限极分类 排序
     * @param $data //排序数据
     * @param $label //关系索引
     * @param int $parent_id //递归参数 默认 0
     * @param int $level //递归参数 默认 0
     * @return array //返回排序的带层级的数据
     */
    protected function Infinite_order_sort($data, array $label = ['pid' => 'parent_id', 'id' => 'id', 'level' => 'level'], $parent_id = 0, $level = 0) {
        // 定义一个数组保存排序好之后的数据
        static $_ret = [];
        foreach ($data as $v) {
            // 父ID
            if ($v[$label['pid']] == $parent_id) {
                // 标签它的级别
                $v[$label['level']] = $level;
                // 挪到排序之后的数组中
                $_ret[] = $v;
                // 找 $v 的子分类    先看这
                $this->Infinite_order_sort($data, $label, $v[$label['id']], $level + 1);
            }
        }
        // 返回排序好的数组
        return $_ret;
    }

    public static function __callstatic($name, $arr) {

        echo "该模型" . __CLASS__ . "不存在静态方法" . $name . lm;

    }

    public function __set($name, $val) {
        $this->fillData[$name] = $val;
    }

    public function __get($name) {
        return $this->fillData[$name];
    }


}


?>