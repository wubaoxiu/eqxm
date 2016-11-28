<?php 
namespace Home\Controller;
use Think\Controller;
/**
    *前台贴吧控制器 LoginController
    *控制器作用    贴吧帖子的操作
    *@作者    yjx
    *@date    2016/11/21
*/
class NoteController extends CommonController
{
    // 定义数据库操作类
    private $_note = null;
    private $_user = null;
    private $_bars = null;
    private $_shutup = null;

    // 初始化
    public function _initialize()
    {
        $this->_note = M('note');
        $this->_user = M('user');
        $this->_bars = M('bars');
        $this->_shutup = M('shutup');
    }

    // 帖子添加
    public function add()
    {
        if(empty($_SESSION['user'])){
            $this->error('您还没有登录，请先登录！',U('Login/index'));
        }
        $title = $_POST['title'];
        if(empty($title)){
            $list = array('val'=>false,"content"=>"标题不能为空！");
            $this->ajaxReturn($list);
        }elseif(strlen($title)>90){
            $list = array('val'=>false,"content"=>"标题不能超过30字！");
            $this->ajaxReturn($list);
        }elseif(strlen($title)<30){
            $list = array('val'=>false,"content"=>"标题不能少于十个字！");
            $this->ajaxReturn($list);
        }
        if(empty($_POST['content'])){
            $this->error('内容不能为空！');
        }
        $map['user_id'] = $_SESSION['user']['id'];
        $map['bar_id'] = $_POST['id'];
        $arr = $this->_bars->where($map)->find();
        if(empty($arr)){
            $this->error("必须关注本贴吧，才能发帖哦！");
            // $list = array('val'=>false,"content"=>"必须关注本贴吧，才能发帖哦！");
            // $this->ajaxReturn($list);exit;
        }
        $arr2 = $this->_shutup->where($map)->find();
        if($arr2['relieve']>time()){
            $a = date('Y-m-d H:i:s',$arr2['relieve']);
            $this->error("您现在还处于禁言期间，解除时间为$a");
        }else{
            $this->_shutup->where($map)->delete();
        }
        
        // dump($_POST['content']);
        $data['user_id'] = $_SESSION['user']['id'];
        $data['bar_id'] = $_POST['id'];
        $data['title'] = $_POST['title'];
        $data['createtime'] = time();
        $data['isfine'] = 1;
        $data['is_show'] = 1;
        $data['scan'] = 0;
        $data['reply'] = 0;
        $data['content'] = $_POST['content'];
        if($this->_note->data($data)->add()>0){
            $this->success('发帖成功！');
        }else{
            $this->error('发帖失败！');
        }
    }

    // 帖子管理 页面
    public function gl_note()
    {
        $id = I('get.id');
        // 查询所有该贴吧中帖子的楼主信息
        $list = $this->_note->where(array('bar_id'=>array('eq',$id),'is_show'=>array('eq',1)))->order('id desc')->select();
        foreach ($list as $k=>$v) {
            // dump($v);
            $list[$k]['louzhu'] = $this->_user->where(array('id'=>array('eq',$v['user_id'])))->find();
        }
        $this->assign('bar_id',$id);
        $this->assign('title','帖吧管理');
        $this->assign('stitle','帖子管理 ');
        $this->assign('data',$list);
        $this->display();
    }

    // 帖子加精
    public function isfine()
    {
        $data['id'] = I('get.id');
        $data['isfine'] = 2;
        if($this->_note->data($data)->save()>0){
            $this->success('加精成功！');
        }else{
            $this->error('加精失败！');
        }
    }
    // 取消帖子加精
    public function nofine()
    {
        $data['id'] = I('get.id');
        $data['isfine'] = 1;
        if($this->_note->data($data)->save()>0){
            $this->success('取消加精成功！');
        }else{
            $this->error('取消加精失败！');
        }
    }
    // 帖子置顶
    public function istop()
    {
        $data['id'] = I('get.id');
        $data['istop'] = 1;
        if($this->_note->data($data)->save()>0){
            $this->success('置顶成功！');
        }else{
            $this->error('置顶失败！');
        }
    }
    // 取消帖子置顶
    public function notop()
    {
        $data['id'] = I('get.id');
        $data['istop'] = 0;
        if($this->_note->data($data)->save()>0){
            $this->success('取消置顶成功！');
        }else{
            $this->error('取消置顶失败！');
        }
    }


    // 删除帖子
    public function noshow()
    {
        $data['id'] = I('get.id');
        $data['is_show'] = 2;
        if($this->_note->data($data)->save()>0){
            $this->success('删除成功！');
        }else{
            $this->error('删除失败！');
        }
    }

}


