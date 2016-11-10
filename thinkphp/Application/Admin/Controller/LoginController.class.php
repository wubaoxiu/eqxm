<?php
namespace Admin\Controller;

use \Think\Verify;

class LoginController extends AdminController 
{
    //获取登录界面
    public function index()
    {
        $this->display('Login/index');
    }

    /*
        *登录响应
        *@return bool ;  true跳转至首页，false返回登录
    */
    public function doLogin()
    {
        // dump($_POST);
        $verify = new Verify();
        $name = I('post.name');
        $password = md5(I('post.password'));
        $yzm = I('post.yzm');
        
        if(!$verify->check($yzm)){
            $this->error("验证码错误！");
        }else{
            echo "YES";
        }
    }

    public function yzm()
    {
        //生成验证码
        $verify = new Verify();
        $verify->fontSize = 23;
        $verify->length = 4;
        $verify->codeSet = "0123456789";
        $verify->imageW = 150;
        // $verify->imageH = 50;
        $verify->bg = array(143,151,154);

        $verify->entry();

    }
}