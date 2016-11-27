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

    public function bars()
    {
        $typeid = I('id');
        $pid = I('pid');
        // echo $pid;

        if(empty($typeid) || empty($pid)){
            $this->error("非法操作");
            exit;
        }
        //查询顶级分类
        $typetitle = M('type')->where(array('id'=>$pid))->find();
        // dump($typetitle);

        //查询所有子分类
        $data = M('type')->where(array('pid'=>$pid))->select();
        // dump($data);
        $typename = M("type")->find($typeid);
        // dump($typename);
        //查询分类下的所有吧
        $bars = M('barinfo')->where(array('type_id'=>$typeid))->select();
        // dump($bars);

        foreach($bars as $v){
            $v['count'] = M('note')->field(array("count(bar_id)"=>"count"))->where(array('bar_id'=>array('eq',$v['id'])))->select();
            $list[] = $v;
        }
        // dump($list);

        $this->assign('typetitle',$typetitle);
        $this->assign('data',$data);
        $this->assign('bars',$list);
        $this->assign('typename',$typename);

        $this->display("Type/bars");
    }
}