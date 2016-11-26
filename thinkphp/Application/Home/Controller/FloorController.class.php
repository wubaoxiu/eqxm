<?php
namespace Home\Controller;

use Think\Controller;

/**
 *  控制器名：FloorController
 *      控制器作用：增加楼层，修改楼层状态(显示不显示)
 *  @author wbx
 *  @date 2016-11-18
*/
class FloorController extends CommonController
{
    public function floorAdd()
    {
        if(empty($_SESSION['user'])){
            $this->error("请先登录！！！");
            exit;
        }
        // dump($_POST);
        $f = D("Floor");
        // dump($f);
        // dump($f->create());
        $noteid = $_POST['note_id'];


        if(!$f->create()){
            $this->error($f->getError());
            exit;
        }

        $reply = M('note')->field('reply')->where(array('id'=>array('eq',$noteid)))->find();
        $rep = $reply['reply']+1;
        $re['id'] = $noteid;
        $re['reply'] = $rep;
        dump($re);

        if($f->add()>0 && M('note')->data($re)->save()>0){
            $this->success("评论成功！！！");
            exit;
        }else{
            $this->error("评论失败！！！");
            exit;
        }
        
    }
}