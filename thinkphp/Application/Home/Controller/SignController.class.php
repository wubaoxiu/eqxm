<?php
namespace Home\Controller;

use Think\Controller;

/**
 *  控制器名：SignController
 *      控制器作用：签到 经验 等级 的获取
 *  @author wbx
 *  @date 2016-11-23
*/
class SignController extends Controller
{
    /**
     * 方法名：allIn()  一键签到  签到所有已关注的吧
     * @return [void]
    */
    public function allIn()
    {
        $bars = M('bars');
        $data = attentionBars();

        foreach ($data as $v) {
            if($v['signin']==0){               
                $bars->where("id={$v['barsid']}")->setInc('integral',8);
                $bars->where("id={$v['barsid']}")->save(array("signin"=>1));
            }
        }

        $this->changeGrade();
        // $this->ajaxReturn(1);
        $this->redirect("Index/index");        
    }

    /**
     * 方法名：changeGrade()  处理对应的等级
     * @param $arr 传入已经加经验的数据
     * @return [void]
    */
    private function changeGrade()
    {
        $list = attentionBars();
        $bars = M('bars');
        // $list = $arr;
        dump($list);
        foreach ($list as $v){
            if($v['integral']>=pow($v['grade'],2)*8){
                $bars->where("id={$v['barsid']}")->setInc('grade',1);
            }
        }
    }
}