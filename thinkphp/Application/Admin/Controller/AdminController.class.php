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
    public function _empty()
    {
        header("HTTP/1.0 404 Not Found");
        $this->display('Public/404');
    }
    public function _initialize()
    {
        //将没有登录的人，返回至登录页面
        if(empty($_SESSION['admin_info'])){
            $this->redirect("Login/login");
            exit;
        }
    

        //权限过滤
        $cname = CONTROLLER_NAME; //获取控制器名
        $aname = ACTION_NAME;     //获取方法名

        // echo $cname.'||'.$aname;


        $nodelist = $_SESSION['admin_info']['nodelist']; //获取权限信息
        // dump($cname);
        // dump($nodelist);
        // dump($nodelist[$cname]);
        // dump($aname);

        // dump($nodelist);

        // dump($_SESSION);

        if($_SESSION['admin_info']['name'] !== 'admin'){
            if(empty($nodelist[$cname]) || !in_array($aname,$nodelist[$cname])){
                $this->error("抱歉，你没有操作权限！");

                exit;
            }
        }
    }
    
}