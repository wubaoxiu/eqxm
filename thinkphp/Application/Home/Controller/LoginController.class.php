<?php 
namespace Home\Controller;
use Think\Controller;

/**
    *前台注册登录控制器 LoginController
    *控制器作用    管理前台的登录注册
    *@作者    yjx
    *@date    2016/11/17
*/

class LoginController extends Controller
{
    // 定义数据库操作类
    private $_user = null;
    private $_shutup = null;

    // 初始化数据库操作类
    public function _initialize()
    {
        $this->_user = M('user');
        $this->_shutup = M('shutup');
    }

    // 加载登录页面
    public function index()
    {
        $this->display('Login/login');
    }

    // 加载注册页面
    public function reg()
    {
        $this->display();
    }

    // 注册时ajax验证用户名是否唯一
    public function test()
    {
        // 接受ajax传递过来的注册用户名
        $name = $_POST['name'];
        // 判断数据库中是否已存在该用户名
        // return $name;
        if($this->_user->where(array('name'=>array('eq',$name)))->find()>0){
            // 存在返回1，不存在返回0
            $this->ajaxReturn(1);
        } else {
            $this->ajaxReturn(0);
        }
    }

    // 生成验证码
    public function yzm()
    {
        $verify = new \Think\Verify();
        $verify->length = 4;
        $verify->fontSize = 20;
        $verify->imageH = 41;
        $verify->imageW = 150;
        // $verify->useImgBg = true;
        $verify->codeSet = "0123456789";
        $verify->entry();
    }

    // ajax验证验证码是否正确
    public function checkyzm()
    {
        $yzm = $_POST['yzm'];
        $verify = new \Think\Verify();
        if(!$verify->check($yzm)){
            $this->ajaxReturn(0);
        }else{
            $this->ajaxReturn(1);
        }
    }

    // 执行注册操作
    public function doReg()
    {
        // 接受来自注册页提交的信息
        $data['name'] = $_POST['uname'];
        $data['password'] = md5($_POST['password']);
        $data['email'] = $_POST['email'];
        $data['createtime'] = time();
        $data['sex'] = 1;
        $data['freeze'] = 0;
        // var_dump($data);
        if($this->_user->data($data)->add()>0){
            $this->success('恭喜您，注册成功！',U('Login/index'));
        }else{
            $this->error('注册失败！',U('Login/reg'));
        }
    }

    // 执行登录
    public function doLogin()
    {
        $name = $_POST['name'];
        $password = md5($_POST['password']);
        $data = $this->_user->where(array('name'=>array('eq',$name)))->find();
        // 判断用户名是否存在
        if(empty($data)){
            $this->ajaxReturn('请输入正确的用户名!');exit;
        }
        // 判断密码是否正确
        if($data['password'] != $password){
            $this->ajaxReturn('密码错误！');exit;
        }
        // 判断是否封号
        if($data['freeze'] != 0){
            $this->ajaxReturn("对不起，由于您的不良行为，你的账号已经被封，将于".date('Y-m-d H:i:s',$data['relieve'])."解封，希望您能改正不良行径~");exit;
        }

    }
}




