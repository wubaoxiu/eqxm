<?php
namespace Home\Controller;

use Think\Controller;

/**
 *  控制器名：ApplyController
 *      控制器作用：增加楼层，修改楼层状态(显示不显示)
 *  @author wbx
 *  @date 2016-11-24
*/
class ApplyController extends CommonController
{
    public function applyBazu()
    {
        $bid = I('bid');
        $uid = $_SESSION['user']['id'];
        
        $grade = M('bars')->field("grade")->where("user_id=$uid and bar_id=$bid")->find();

        $result = M('barinfo')->where(array('user_id'=>array('eq',$uid)))->find();

        if($result){
            $this->ajaxReturn(3);
            exit;
        }

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
        $uid = $_SESSION['user']['id'];
        
        //查询等级
        $grade = M('bars')->field("grade")->where("user_id=$uid and bar_id=$bid")->find();
        
        //查询是否是本吧吧主
        $result = M('barinfo')->where(array('user_id'=>array('eq',$uid)))->find();

        //查询是否是本吧管理员
        $admin = M('baradmin')->where("user_id=$uid and bar_id=$bid")->find();
        
        if($result){
            if($result['id'] == $bid){
                $this->ajaxReturn(3);
                exit;
            }
        }

        if($admin){
            $this->ajaxReturn(4);
            exit;
        }

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