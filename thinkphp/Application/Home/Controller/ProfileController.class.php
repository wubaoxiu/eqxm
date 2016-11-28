<?php

namespace Home\Controller;

use Think\Controller;

/**
 * 个人中心  我的帖子 短信验证 修改新密码
 * @author xiao
 * date 2016-11-20
 *****/
class ProfileController extends CommonController
{

    private $_user = null;//用户表操作
    private $_note = null;//用户表操作
    private $_fans = null;//用户表操作

    public function _initialize()
    {
        $this->_user = M('user');
        $this->_note = M('note');
        $this->_fans = M('fans');
    }

    //个人中心首页显示 
    public function index()
    {
        //判断有无登录
        if (empty($_SESSION['user'])) {
            $this->error('您还没有登录，请先登录！');
            exit;
        }
        $id = $_SESSION['user']['id'];
        //根据id查找该用户的信息
        $list = $this->_user->find($id);
        //查询粉丝、关注数量
        $fan = $this->_fans->field('user_id')->where(array('fuser_id'=>array('eq',$id)))->count();
        $follow = $this->_fans->field('fuser_id')->where(array('user_id'=>array('eq',$id)))->count();
        $this->assign('list',$list);
        $this->assign('fan',$fan);
        $this->assign('follow',$follow);
        $this->display();

    }

    // 选择短信验证处理方法
    public function choose()
     {
        $id = $_SESSION['user']['id'];
        $list = $this->_user->where(array('id' => array('eq', $id)))->find();
        $this->assign('list', $list);
        $this->display();

     }

    // 获取最新的验证码
    public function newyzm()
     {  
        $sms = I('post.sms');
        if ($sms == session('sms')) {
            $this->ajaxReturn(true);
        } else {
            $this->ajaxReturn(false);
        }
     }

    // 修改新密码页面
    public function updatepwd()
     {
        $list = $this->_user->find();
        $this->assign('list', $list);
        $this->display();
     }

    // 判断原密码是否与原密码一致
    public function oldpwd(){
        $pwd = I('post.oldpwd/d');
        $oldpass = md5($pwd);
        $password = $_SESSION['user']['password'];
        if ($oldpass == $password) {
           $this->ajaxReturn(true);
        }else{
            $this->ajaxReturn(false);
        }
    }


    // 执行密码修改
    public function dopwd()
     {
        // var_dump($_POST);
        $id = I('post.id/d');
        $data['password'] = md5($_POST['newpwd']);
        $res = $this->_user->where(array('id' => array('eq', $id)))->save($data);
        if ($res !== false) {
            $this->success('修改密码成功', U('Set/detail'));
        } else {
            $this->error('修改密码失败......');
        }
     }

    //我的帖子
    public function mynote(){
        $id = $_SESSION['user']['id'];
        $list = $this->_note->field('bi.name,bi.id bid,n.title,n.createtime,n.id')->where("n.user_id='$id' and n.bar_id=bi.id")->table('csw_barinfo bi,csw_note n')->select();
        $this->assign('list',$list);
        $this->display('Profile/mynote');
    } 

}
