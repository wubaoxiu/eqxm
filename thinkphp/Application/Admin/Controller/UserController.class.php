<?php
namespace Admin\Controller;

class UserController extends AdminController
{
    /*
        获取普通用户列表
    */
    public function index(){
        $data = array(
            array('id'=>1,'name'=>'bx','email'=>'123@163.com'),
            array('id'=>2,'name'=>'dm','email'=>'124@163.com'),

        );
        $this->assign('userlist',$data);
        $this->assign('title','普通用户列表');
        $this->assign('name','吴宝秀');
        $this->display('User/index');
    }

    /*
        获取添加用户页面
    */
    public function add(){
        $this->assign('title','普通用户列表');
        $this->assign('name','吴宝秀');
        $this->display('User/add');
    }
}