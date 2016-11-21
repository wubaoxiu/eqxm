<?php 
namespace Home\Controller;

use Think\Controller;



/*
 * 控制器名：BarController
 *     控制器作用：加载贴子楼层页面，处理贴子评论、楼层评论
 * @author wbx
 * @date 2016-11-21
*/

class BarController extends Controller
{
    // 定义数据库操作类
    private $_barinfo = null;
    private $_note = null;
    private $_user = null;

    // 初始化
    public function _initialize()
    {
        $this->_barinfo = M('barinfo');
        $this->_note = M('note');
        $this->_user = M('user');
    }

    // 主页
    public function index()
    {

        $id = 1;
        $data = $this->_barinfo->where(array('id'=>array('eq',$id)))->find();
        // dump($data);
        if(empty($data)){
            U('index/index');exit;
        }
        $list = $this->_note->where(array('bar_id'=>array('eq',$id)))->order('id desc')->select();
        foreach ($list as $k=>$v) {
            // dump($v);
            $list[$k]['louzhu'] = $this->_user->where(array('id'=>array('eq',$v['user_id'])))->find();
        }
        $attenbars = attentionBars();
        // dump($list);die;
        $this->assign('list',$list);
        $this->assign('louzhu',$louzhu);
        $this->assign('data',$data);
        $this->assign('attenbars',$attenbars);

        // dump($attenbars);
        $this->display();
    }

    /**
     * 方法名：note()  显示贴子内容页面
     * @return [void]
    */
    public function note()
    {
        // $barid = I('bar_id');
        $barid = 3;
        // $noteid = I('note_id');
        $noteid = 1;

        $atten = $this->is_attentionBar($barid);

        $attenbars = attentionBars();
        // echo $atten;

        //贴子主要信息
        $count = M('note')->field(array('count(bar_id)'=>"count"))->where("bar_id=$barid")->select();
        $data = M('note')->field(array("b.name bname","n.bar_id","b.attention","b.hpic bhpic","u.hpic uhpic","u.name uname","n.content","n.createtime","n.title"))->table("csw_barinfo b,csw_note n,csw_user u")->where("b.id=$barid and n.user_id=u.id and n.id=$noteid")->select();
        // dump($data);
        // dump($count);
        //贴吧楼层
        $replys = M('floor')->field(array("f.content","f.note_id","u.name uname","u.hpic uhpic","u.id uid","f.createtime","f.floor"))->table("csw_user u,csw_floor f")->where("f.note_id=$noteid and f.user_id=u.id")->select();

        //楼层评论
        $comments = M('comments')->field("u.name uname,c.content,c.createtime,u.hpic,c.floor cfloor")->table("csw_user u,csw_comments c")->where("c.note_id=$noteid and c.user_id=u.id and c.status=1")->select();
        // dump($comments);
        // dump($replys);

        $this->assign('count',$count);
        $this->assign('data',$data);
        $this->assign('comments',$comments);
        $this->assign("replys",$replys);
        $this->assign("atten",$atten);
        $this->assign("attenbars",$attenbars);

        $this->display("Bar/note");
    }

    /**
     * 方法名：doComment()  层内评论
     * @return ajax
    */
    public function doComment()
    {
        if(empty($_SESSION['user'])){
            $this->error("你还未登录，请先登录！");
            exit;
        }
        $comment = M('Comments');

        $data = array();
        $data['note_id'] = I("post.note_id");
        $data['user_id'] = I("post.user_id");
        $data['createtime'] = time();
        $data['floor'] = I("post.floor");
        $data['content'] = I("post.content");
        // dump($data);exit;

        if(!$data){
            $this->ajaxReturn(0);
            exit;
        }else{
            $cid = $comment->data($data)->add();

            $this->ajaxReturn($cid);
        }
    }
    
    /**
     * 方法名：attentionBars()  关注贴吧
     * @return [void]
    */
    public function attentionBars()
    {
        if(empty($_SESSION['user'])){
            $this->error("你还未登录，请先登录！");
            exit;
        }

        $bar_id = I("barid");
        if(empty($bar_id)){
            $this->error("操作失误！！！");
            exit;
        }

        $data['bar_id'] = $bar_id;
        $data['user_id'] = $_SESSION['user']['id'];
        dump($data);
        $bars = M('bars');

        if($bars->data($data)->add()>0){
            $this->success("关注成功！！！");
            exit;
        }else{
            $this->error("关注失败！！！");
            exit;
        }
    }

    /**
     * 方法名：is_attentionBar()  判断是否关注此吧
     * @param [int] $barid  吧ID
     * @return [string]  0为未关注 1为已关注
    */
    private function is_attentionBar($barid)
    {
        $map = [];
        $map['user_id'] = $_SESSION['user']['id'];
        $map['bar_id'] = $barid;
        $res = M('bars')->where($map)->select();
        // dump($res);
        if(empty($res)){
            return 0;
        }else{
            return 1;
        }
    }
}



