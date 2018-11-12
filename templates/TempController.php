
namespace <?=$namespace?>;
<?= ($namespace =='app\controllers') ? "\r\n" :  'use app\\controllers\\Controller;'."\r\n" ?>
use core\Request;
<?= (isset($this->group))? "use ".$this->mspace."\\$fileName;"."\r\n" : "" ?>

class <?=$fileName?>Controller extends Controller {

    // 显示列表
    function index() {
        $<?=$name?> = new <?=$fileName?>;
        $data = $<?=$name?>->get();
        view('<?=$name?>.index',['data'=>$data]);
    }

    // 显示 添加页
    function add(){
        
        view('<?=$name?>.create');
    }

    // 添加
    function insert(Request $req,$id) {

        $data = $req->all();
        $<?=$name?> = new <?=$fileName?>;
        $<?=$name?>->fill($data);
        $<?=$name?>->exec_insert($<?=$name?>->getFillData());
        message('数据添加成功',1,Route('<?=$name?>.index'),3);
        // redirect(Route('<?=$name?>.index'));
    }

    // 删除
    function del(Request $req,$id){

        // $data = $req->all();
        $<?=$name?> = new <?=$fileName?>;
        $<?=$name?>->where($id)
            ->delete();
        message('数据删除成功',1,Route('<?=$name?>.index'),3);
    }
    
    // 显示 修改页
    function mod(Request $req,$id){

        $data = $req->all();
        $<?=$name?> = new <?=$fileName?>;
        $data = $<?=$name?>->where($id)->get()[0];
        view('<?=$name?>.edit',['data'=>$data]);
    }

    // 修改
    function update(Request $req,$id) {
        
        $data = $req->all();
        $<?=$name?> = new <?=$fileName?>;
        $<?=$name?>->where($id)
            ->fill($data)
            ->update();
        message('数据更改成功',1,Route('<?=$name?>.index'),3);
        
    }

    // 搜索
    function search(Request $req,$id){
    }

}

?>