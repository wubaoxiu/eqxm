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

        $uid = $_SESSION['user']['id'];
        // echo $uid;
        $data = $bars->field('b.name,ba.signtime,b.id,ba.integral,ba.grade,ba.id barsid')->table("csw_bars ba,csw_barinfo b")->where("ba.user_id=$uid and ba.bar_id=b.id and grade=2")->select();
        // dump($data);
        //获取现在的时间并格式化成对比时间格式(年月日)
        $nowtime = date("ymd",time());
        $now = time();
        foreach ($data as $v) {
            $d = date("ymd",$v['signtime']);
            //如果时间与今天不是同一天则认为没有签到，进行签到动作
            if($d !== $nowtime){               
                $bars->where("id={$v['barsid']}")->setInc('integral',8);
                $bars->where("id={$v['barsid']}")->save(array("signtime"=>$now));
            }
        }

        $this->changeGrade();
        // $this->ajaxReturn(1);
        $this->redirect("Index/index");        
    }

    public function in()
    {
        $bars = M('bars');
        $adr = I('adr');
        if(empty($adr)){
            $this->error("非法操作！！！");
            exit;
        }
        $map = [];
        $map['bar_id'] = I('barid');
        $map['user_id'] = $_SESSION['user']['id'];
        // dump($map);
        $data = M('bars')->where($map)->find();
        // echo $data['id'];
        // dump($data);exit;
        $now = time();
        $nowtime = date("ymd",$now);
        $signtime = date("ymd",$data['signtime']);

        if($nowtime !== $signtime){
            $bars->where("id={$data['id']}")->setInc('integral',8);
            $bars->where("id={$data['id']}")->save(array("signtime"=>$now));
        }

        $this->changeGrade();
        // $this->ajaxReturn(1);
        if($adr == 'ind'){
            $this->redirect("Bar/index?id={$map['bar_id']}");
        }else{
            $noteid = I('noteid');
            $this->redirect("Bar/note?bar_id={$map['bar_id']}&note_id=$noteid");
        }
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