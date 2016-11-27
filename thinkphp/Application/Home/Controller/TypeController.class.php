<?php
namespace Home\Controller;

use Think\Controller;

/**
 *  控制器名：TypeController
 *      控制器作用：增加楼层，修改楼层状态(显示不显示)
 *  @author wbx
 *  @date 2016-11-26
*/
class TypeController extends CommonController
{
    public function index(){
        //贴吧分类
        $typehead = M('type')->where(array('pid'=>array('eq',0)))->select();
        $types = M('type')->select();
        // dump($typehead);
        // dump($types);

        $this->assign("typehead",$typehead);
        $this->assign('types',$types);

        $this->display("Type/index");
    }
}