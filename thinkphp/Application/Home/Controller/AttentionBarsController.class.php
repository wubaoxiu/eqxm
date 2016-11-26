<?php
namespace Home\Controller;

use Think\Controller;

/**
 *  控制器名：AttentionBarsController
 *      控制器作用：关注贴吧，取消关注
 *  @author wbx
 *  @date 2016-11-24
*/
class AttentionBarsController extends Controller
{
    /**
     * 方法名：attentionBars()  关注贴吧
     * @return [void]
     */
    public function attentionBars()
    {
        if (empty($_SESSION['user'])) {
            $this->error("你还未登录，请先登录！");
            exit;
        }

        $bar_id = I("barid");
        // dump($bar_id);
        if (empty($bar_id)) {
            $this->error("操作失误！！！");
            exit;
        }

        $data['bar_id'] = $bar_id;
        $data['user_id'] = $_SESSION['user']['id'];
        // dump($data);
        $bars = M('bars');


        if($bars->data($data)->add()>0){
            M('barinfo')->where(array('id'=>array('eq',$data['bar_id'])))->setInc('attention');

            $this->ajaxReturn(1);
            // exit;
        } else {
            $this->ajaxReturn(0);
            // exit;
        }
    }

    /**
     * 方法名：cancelBars()  取消贴吧关注 一切经验等级都将归于0
     * @return [void]
    */
    public function cancelBars()
    {
        $bar_id = I("barid");
        if(empty($bar_id)){
            $this->error("操作失误！！！");
            exit;
        }

        $data['bar_id'] = $bar_id;
        $data['user_id'] = $_SESSION['user']['id'];
        // dump($data);exit;
        $bars = M('bars');

        $res = M('baradmin')->where($data)->find();

        if($res){
            M('baradmin')->where($data)->delete();
        }

        $result = M('barinfo')->where("user_id={$data['user_id']}")->save(array('user_id'=>""));

        if($bars->where($data)->delete()>0){
            M('barinfo')->where(array('id'=>array('eq',$bar_id)))->setDec('attention');
            $this->ajaxReturn(1);
        }else{
            $this->ajaxReturn(0);
        }
    }
}