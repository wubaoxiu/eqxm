<?php
namespace Home\Controller;

use Think\Controller;

/**
 *  控制器名：ApplyController
 *      控制器作用：增加楼层，修改楼层状态(显示不显示)
 *  @author wbx
 *  @date 2016-11-24
*/
class ApplyController extends Controller
{
    public function applyBazu()
    {
        $bid = I('bid');
        // echo $bid;
        $uid = $_SESSION['user']['id'];
        // echo $uid;
        $grade = M('bars')->field("grade")->where("user_id=$uid and bar_id=$bid")->find();
        // dump($grade);
        if($grade['grade'] < 5){
            $this->ajaxReturn(1);
            exit;
        }else{
            $map = [];
            $map['user_id'] = $uid;
            $map['bar_id'] = $bid;
            $map['status'] = 1;
            if(M('request')->data($map)->add()>0){
                $this->ajaxReturn(2);
                exit;
            }else{
                $this->ajaxReturn(0);
            }
        }

    }

    public function applyAdmin()
    {
        $bid = I('bid');
        // echo $bid;
        $uid = $_SESSION['user']['id'];
        // echo $uid;
        $grade = M('bars')->field("grade")->where("user_id=$uid and bar_id=$bid")->find();
        // dump($grade);
        if($grade['grade'] < 5){
            $this->ajaxReturn(1);
            exit;
        }else{
            $map = [];
            $map['user_id'] = $uid;
            $map['bar_id'] = $bid;
            $map['status'] = 2;
            if(M('request')->data($map)->add()>0){
                $this->ajaxReturn(2);
                exit;
            }else{
                $this->ajaxReturn(0);
            }
        }

    }
}