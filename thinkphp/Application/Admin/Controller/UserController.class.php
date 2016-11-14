<?php
namespace Admin\Controller;

class UserController extends AdminController
{
    /*
        获取普通用户列表
    */
    public function index(){
        $data = M('user')->field('u.id,u.name,u.sex,u.email,r.role')->table('csw_user u,csw_role r')->where('u.role_id=r.id')->select();
        // var_dump($data);
        $this->assign('userlist',$data);
        $this->assign('title','普通用户列表');
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