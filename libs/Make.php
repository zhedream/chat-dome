<?php

namespace libs;


/**
 * 代码生成器
 */
class Make {

    public $isView = false;
    public $group = false;

    public $cspace = null; // 控制器 命名空间
    public $mspace = null; // 模型 命名空间

    public $table = null; // 表名
    
    public $cname = null; // 控制器名 小写
    public $mname = null; // 模型名 小写
    public $cFname = null; // 控制器名 大写
    public $mFname = null; // 模型名 大写

    public $cdir = null; // 控制器 路径
    public $mdir = null; // 模型 路径

    public $fields = []; // 表 字段
    
    function controller($name) {

        
        
        list($namespace,$fileName) = $this->parsePath($name);
        $this->cspace = $namespace;

        $name = strtolower($fileName);
        $this->cname = $name;
        $this->cFname = $fileName;

        $dir = ROOT.$namespace;
        is_dir($dir) OR mkdir($dir, 0777, true);
        
        if(file_exists(ROOT.$namespace.'/'.$fileName.'Controller.php')){
            echo '存在 '.ROOT.$namespace.'/'.$fileName.'Controller.php'."\r\n";
            $this->cdir = ROOT.$namespace.'/'.$fileName.'Controller.php';
            return $this;
        }

        ob_start();
        include(ROOT . 'templates/TempController.php');
        $str = ob_get_clean();
        file_put_contents(ROOT.$namespace.'/'.$fileName.'Controller.php', "<?php\r\n".$str);
        $this->cdir = ROOT.$namespace.'/'.$fileName.'Controller.php';

        // regist Route
        $putRoute = "// $name
Route::get('/$name/index','app/controllers/{$fileName}Controller@index')->name('$name.index'); // 显示列表
Route::get('/$name/search','app/controllers/{$fileName}Controller@search')->name('$name.search'); // 搜索
Route::get('/$name/add','app/controllers/{$fileName}Controller@add')->name('$name.add'); // 显示 添加
Route::post('/$name/insert','app/controllers/{$fileName}Controller@insert')->name('$name.insert'); // 添加
Route::get('/$name/del/{id}','app/controllers/{$fileName}Controller@del')->name('$name.del'); // 删除 post
Route::get('/$name/mod/{id}','app/controllers/{$fileName}Controller@mod')->name('$name.mod'); // 显示 修改
Route::post('/$name/update/{id}','app/controllers/{$fileName}Controller@update')->name('$name.update'); // 修改
        ";
        $f = fopen(ROOT."route/web.php",'a');
        fwrite($f,$putRoute."\r\n");
        fclose($f);
        

        echo "sucessful: ".$this->cdir."\r\n";
        return $this;
    }

    function model($name,$table=null) {

        // var_dump(func_get_args());die;
        // var_dump($name,$table);die;
        
        list($namespace,$fileName) = $this->parsePath($name);
        $this->mspace = $namespace;

        $name = strtolower($fileName);
        $this->mname = $name; // 小写
        $this->mFname = $fileName; // 大写
        
        
            $class = $fileName;
            $last = substr($class,-1);
            if($last == 's')
                $class = $class;
            else
                $class = $class.'s';
    
            $class = lcfirst($class);
            $class = preg_replace_callback('/([A-Z])+/',function($matches){
                return "_".strtolower($matches[1]);
            },$class);
            $prefix = $GLOBALS['config']['db']['prefix'];
            if($table)
                $table = $prefix. $table;
            else
                $table = $prefix. $class;

            $this->table = $table;
        $pdo = \core\DB::getDB();
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_SILENT); //ERRMODE_SILENT  ERRMODE_WARNING
        
        $sql = "SHOW FULL FIELDS FROM ".$this->table;
        // 取出表信息
        $fields = \core\DB::findAll($sql);
        if($fields){

            $this->fields = $fields;
            foreach ($fields as  $val) {
                if($val['Key'] =='PRI' || $val['Field'] =='created_at' || $val['Field'] =='updated_at' )
                    continue;
                $fillables[] = "'".$val['Field']."'";
            }
            $fillableStr = '['.implode(',',$fillables).']';
            
        }else{
            $fields= [];
            $this->fields = [];
            $fillableStr = '[]';
        }

        

        $dir = ROOT.$namespace;
        is_dir($dir) OR mkdir($dir, 0777, true);

        if(file_exists(ROOT.$namespace.'/'.$fileName.'.php')){
            echo '存在 '.ROOT.$namespace.'/'.$fileName.'.php'."\r\n";
            $this->mdir = ROOT.$namespace.'/'.$fileName.'.php';
            return $this;
        }

        ob_start();
        include(ROOT . 'templates/Temp.php');
        $str = ob_get_clean();
        file_put_contents(ROOT.$namespace.'/'.$fileName.'.php', "<?php\r\n".$str);
        $this->mdir = ROOT.$namespace.'/'.$fileName.'.php';

        echo "sucessful: ".$this->mdir."\r\n";
        return $this;
    }

    function view($name) {


        $sql = "SHOW FULL FIELDS FROM ".$this->table;
        // 取出表信息
        $fields = \core\DB::findAll($sql);
        $this->fields = $fields;

        $putRoute_index = "<?=Route('$name.index')?>";
        $putRoute_add = "<?=Route('$name.add')?>";
        $putRoute_insert = "<?=Route('$name.insert')?>";
        $putRoute_del = "<?=Route('$name.del',['id'=>\$v['id']])?>";
        $putRoute_mod = "<?=Route('$name.mod',['id'=>\$v['id']])?>";
        $putRoute_update = "<?=Route('$name.update',['id'=>\$data['id']])?>";
        $putRoute_search = "<?=Route('$name.search')?>";
        $csrf_field = "<?=csrf_field()?>";
        $csrf = "<?=csrf()?>";
        
        is_dir(ROOT . 'views/'.$name) OR mkdir(ROOT . 'views/'.$name, 0777);
        // create.html
        ob_start();
        include(ROOT . 'templates/create.html');
        $str = ob_get_clean();
        if(file_exists(ROOT.'views/'.$name.'/create.html'))
            echo '存在 '.ROOT.'views/'.$name.'/create.html'."\r\n";
        else{
            file_put_contents(ROOT.'views/'.$name.'/create.html', $str);
            echo "sucessful: ".ROOT.'views/'.$name.'/create.html'."\r\n";
        }
        // edit.html
        ob_start();
        include(ROOT . 'templates/edit.html');
        $str = ob_get_clean();
        if(file_exists(ROOT.'views/'.$name.'/edit.html'))
            echo '存在 '.ROOT.'views/'.$name.'/edit.html'."\r\n";
        else{
            file_put_contents(ROOT.'views/'.$name.'/edit.html', $str);
            echo "sucessful: ".ROOT.'views/'.$name.'/edit.html'."\r\n";
        }
        // index.html
        ob_start();
        include(ROOT . 'templates/index.html');
        $str = ob_get_clean();
        if(file_exists(ROOT.'views/'.$name.'/index.html'))
            echo '存在 '.ROOT.'views/'.$name.'/index.html'."\r\n";
        else{
            file_put_contents(ROOT.'views/'.$name.'/index.html', $str);
            echo "sucessful: ".ROOT.'views/'.$name.'/index.html'."\r\n";
        }
    }

    function group($name,$table=null) {

        // var_dump(func_get_args());die;
        // var_dump($name,$table);die;
        $this->group = true;
        if($table)
            $this->model($name,$table);
        else
            $this->model($name);

        $this->controller($this->mFname);
        $this->view($this->mname);

    }

    /**
     * 解析 命名空间 文件名
     */
    function parsePath($name){
        $mode =  debug_backtrace()[1]['function'];
        // dd($mode);
        $namespace = "app\\".$mode."s";
        
        if(preg_match('/\//',$name)){
            // 存在  命名空间
            $name = preg_replace('/\//','\\',$name);
            $name = $namespace."\\".$name;
            $index = strrpos($name,'\\');
            $namespace = substr($name,0,$index);
            $fileName = substr($name,$index+1);
            // dd($fileName);

        }else{

            $fileName = $name;
        }
        return [$namespace,$fileName];
    }

}