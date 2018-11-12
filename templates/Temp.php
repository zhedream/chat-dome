
namespace <?=$namespace?>;
<?= ($namespace =='app\models') ? "\r\n" :  'use app\\models\\Model;'."\r\n" ?>
use core\RD; // redis 类
use core\DB; // DB 类

class <?=$fileName?> extends Model {
    
    protected $table = '<?=$this->table?>';
    protected $fillable = <?=$fillableStr?>;


}