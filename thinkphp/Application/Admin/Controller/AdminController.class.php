<?php
namespace Admin\Controller;
use \Think\Controller;
 /**
    *   控制器名称 AdminController
    *       控制器作用 初始化首页
    *   @author     wbx
    *   @date 2016/11/10
    */

class AdminController extends Controller 
{
    public function _initialize()
    {
        //将没有登录的人，返回至登录页面
        if(empty($_SESSION['admin_info'])){
            $this->redirect("Login/login");
            exit;
        }
    }

    // $cname = CONTROLLER_NAME; //获取控制器名
    // $aname = ACTION_NAME;     //获取方法名
}