<?php
namespace Admin\Controller;

use \Think\Controller;
use \Think\Verify;

/*
    * 控制器名称  LoginController
    * 控制器作用  处理登录、退出
    * @author   wbx
    * @date     2016-11-13
*/

class LoginController extends Controller 
{
    //获取登录界面
    public function login()
    {
        $this->display('Login/login');
    }

    /*
        * 登录响应
        * @return bool ;  true跳转至首页，false返回登录
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
            exit;
        }

        $model = M('admin');
        $data = $model->where(array('name'=>$name))->find();
        // dump($data);

        if(is_null($data)){
            $this->error("该用户名不存在！");
            exit;
        }

        if($password!=$data['password']){
            $this->error("您输入的密码有误！");
            // echo $password."<br>";
            // echo $data['password'];
            exit;
        }
        $_SESSION['admin_info'] = $data;
        // dump($_SESSION['admin_info']);
        $list = M('node')->field('cname,aname')->where('id in'.M('role_node')->field('node_id')->where("role_id in ".M('user_role')->field('role_id')->where(array('user_id'=>array('eq',$data['id'])))->buildSql())->buildSql())->select();

        // dump($list);

        //控制器名转换为大写
        foreach($list as $k=>$v){
            $list[$k]['cname'] = ucfirst($v['cname']);
        }

        $nodelist = array();
        $nodelist['Index'] = array('index');

        //遍历重新拼装
        foreach($list as $v){
            $nodelist[$v['cname']][] = $v['aname'];
            //将获取添加页面与执行添加，获取修改页面与执行修改，拼装到一起
            if($v['aname'] == 'add'){
                $nodelist[$v['cname']][] = "doAdd";
            }
            if($v['aname'] == 'edit') {
                $nodelist[$v['cname']][] = "save";
            }
        }
            // dump($nodelist);

        $_SESSION['admin_info']['nodelist'] = $nodelist;

        // dump($_SESSION);

        $this->redirect('Index/index');
    }

    /*
        *生成验证码
    */
    public function yzm()
    {
        $verify = new Verify();
        $verify->fontSize = 23;
        $verify->length = 1;
        $verify->codeSet = "0123456789";
        $verify->imageW = 150;
        // $verify->imageH = 50;
        $verify->bg = array(143,151,154);

        $verify->entry();

    }

    /*
        * 退出登录
    */
    public function logout()
    {
        unset($_SESSION['admin_info']);
        $this->redirect('Login/login');
    }
}