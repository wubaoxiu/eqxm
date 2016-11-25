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
        $data['hpic'] = 'Public/linkimg/2016-11-15/s_582af894f13db.jpg';
        $data['sex'] = 1;
        $data['freeze'] = 0;
        $data['logintime'] = time();
        // var_dump($data);

        $userId = $this->_user->data($data)->add();

        // 自定义邮件发送内容
        $header = "<p>您好！</p>";
        $url = 'http://' . I('server.HTTP_HOST') . __APP__ . '/Home' . '/Login/verifyEmail/id/' . $userId . '/active_code/' . $data['password'];
        $username   = '<p>您于' . date('Y年m月d日 H时i分s秒') . '申请注册CSW贴吧账号<strong style="color:#00acff;">' . $data['name'] . '</strong></p>';
        $url        ='<a href="' . $url .'">' . $url . '</a>';
        $content = $header.$username.$url;

        if($userId>0){
            $this->sendMail($data['email'],$data['name'],'CSW贴吧验证消息',$content);
            // $this->redirect("Login/loading?email={$data['email']}");
            $this->success("我们已将您的验证发送至邮箱，请登录注册邮箱在规定时间内验证吧！(请勿关闭此页面)",U('Login/loading',array('id'=>$userId)),255);
        }else{
            $this->error('注册失败！',U('Login/reg'));
        }
    }

    public function loading()
    {
        $id = I('id');
        // echo $id;
        if(empty($id)){
            $this->redirect("Login/reg");
        }

        $email_status = $this->_user->field('email_status')->find($id);

        dump($email_status);
        if($email_status == 0){
            if($this->_user->delete($id)>0){
                $this->redirect("Login/reg");
            }else{
                $this->redirect("Login/reg");
            }
        }
        
    }

    public function verifyEmail()
    {
        $userid = I("id");
        $active_code = I("active_code");
        // echo $userid.'||'.$active_code;

        if(empty($userid)){
            $this->error("非法操作！！！");
            exit;
        }

        $res = $this->_user->field('email_status,password')->find($userid);
        // dump($res);

        if($res){           
            //防止多次激活
            if($res['email_status']==1){
                $this->error("该用户已经激活，链接失效！");
                exit;
            }

            if($active_code == $res['password'] && $this->_user->where(array('id'=>array('eq',$userid)))->save(array('email_status'=>1))>0){
                $this->success("恭喜您，邮箱注册成功！快去贴吧玩玩吧",U('Login/index'));
                exit;
            }else{
                $this->error("邮箱注册失败/(ㄒoㄒ)/~~");
                exit;
            }
        }else{
            $this->error("该链接已经失效！");
            exit;
        }

    }
    

    // 登录验证
    public function checkLogin()
    {
        $name = $_POST['name'];
        $password = md5($_POST['password']);
        $data = $this->_user->where(array('name'=>array('eq',$name)))->find();
        // 判断用户名是否存在
        if(empty($data)){
            $list = array('val'=>false,'content'=>'请输入正确的用户名!');
            $this->ajaxReturn($list);exit;
        }
        // 判断密码是否正确
        if($data['password'] != $password){
            $list = array('val'=>false,'content'=>'请输入正确的密码!');
            $this->ajaxReturn($list);exit;
        }
        // 判断是否封号
        if($data['freeze'] != 0){
            $time = time();
            // 判断封号时间，小于则报封号，小于则报解封号提示
            if($data['relieve']>$time){
                $list = array("val"=>false,"content"=>"对不起，由于您的不良行为，你的账号已经被封，将于".date('Y-m-d H:i:s',$data['relieve'])."解封，希望您能改正不良行径~");
                $this->ajaxReturn($list);exit;
            }else{
                $arr['id'] = $data['id'];
                $arr['freeze'] = 0; 
                $this->_user->data($arr)->save();
                $list = array('val'=>false,"content"=>"尊敬的用户，您曾经的不良行径导致封号，现在已经解封，希望您以后可以遵守CSW贴吧规定！  点击确定即可登录！");
                $this->ajaxReturn($list);
            }            
        }
        if($data['email_status'] != 0){
            $this->_user->where(array('email_status'=>array('neq',0)))->delete();
            $list = array('val'=>false,"content"=>"您还没有注册成功，请重新注册！");
            $this->ajaxReturn($list);
        }

        $list = array("val"=>true);
        $this->ajaxReturn($list);
    }

    // 执行登录
    public function doLogin()
    {
        $name = $_POST['name'];
        $data = $this->_user->where(array('name'=>array('eq',$name)))->find();
        $_SESSION['user'] = $data;
        // 检测用户是否存在被禁言
        $shutup = $this->_shutup->where(array('user_id'=>array('eq',$data['id'])))->select();
        if(!empty($shutup)){
            foreach ($shutup as $v) {
            // dump($v);
            // 收集禁言信息
            $st_info[$v['bar_id']] = $v['relieve']; 
            }
            // dump($st_info);
        }
        $_SESSION['shutup'] = $st_info;
        // dump($_SESSION);

        if($_SESSION['user']){
            $this->success("欢迎回来！！！",U('Index/index'));
        }
    }

    /**
    * 方法名：logout() 退出登录
    * @return
    */
    public function logout()
    {
        unset($_SESSION['user']);
        $this->redirect('Login/login');
    }



    /** 
     * 发送邮件方法
     * @param $to：[接收人邮箱]  $toName：[接收人名]  $title：[标题]  $content：[邮件内容]
     * @return bool true:发送成功 false:发送失败
     */
    function sendMail($to, $toName, $title, $content){

        //引入PHPMailer的核心文件 使用require_once包含避免出现PHPMailer类重复定义的警告
        require_once("./Application/Common/Vendor/PHPMailer/class.phpmailer.php"); 
        require_once("./Application/Common/Vendor/PHPMailer/class.smtp.php");
        //实例化PHPMailer核心类
        $mail = new \PHPMailer();

        //是否启用smtp的debug进行调试 开发环境建议开启 生产环境注释掉即可 默认关闭debug调试模式
        // $mail->SMTPDebug = 1;

        //使用smtp鉴权方式发送邮件
        $mail->isSMTP();

        //smtp需要鉴权 这个必须是true
        $mail->SMTPAuth = true;

        //链接qq域名邮箱的服务器地址
        $mail->Host = 'smtp.qq.com';

        //设置使用ssl加密方式登录鉴权
        $mail->SMTPSecure = 'ssl';

        //设置ssl连接smtp服务器的远程服务器端口号，以前的默认是25，但是现在新的好像已经不可用了 可选465或587
        $mail->Port = 465;

        //设置smtp的helo消息头 这个可有可无 内容任意
        // $mail->Helo = 'Hello smtp.qq.com Server';

        //设置发件人的主机域 可有可无 默认为localhost 内容任意，建议使用你的域名
        $mail->Hostname = 'localhost';

        //设置发送的邮件的编码 可选GB2312 我喜欢utf-8 据说utf8在某些客户端收信下会乱码
        $mail->CharSet = 'UTF-8';

        //设置发件人姓名（昵称） 任意内容，显示在收件人邮件的发件人邮箱地址前的发件人姓名
        $mail->FromName = 'CSW';

        //smtp登录的账号 这里填入字符串格式的qq号即可
        $mail->Username ='1075796211@qq.com';

        //smtp登录的密码 使用生成的授权码（就刚才叫你保存的最新的授权码）
        $mail->Password = 'qgogwugzakjjjdaj';

        //设置发件人邮箱地址 这里填入上述提到的“发件人邮箱”
        $mail->From = '1075796211@qq.com';

        //邮件正文是否为html编码 注意此处是一个方法 不再是属性 true或false
        $mail->isHTML(true); 

        //设置收件人邮箱地址 该方法有两个参数 第一个参数为收件人邮箱地址 第二参数为给该地址设置的昵称 不同的邮箱系统会自动进行处理变动 这里第二个参数的意义不大
        $mail->addAddress($to, $toName);

        //添加多个收件人 则多次调用方法即可
        // $mail->addAddress('xxx@163.com','lsgo在线通知');

        //添加该邮件的主题
        $mail->Subject = $title;

        //添加邮件正文 上方将isHTML设置成了true，则可以是完整的html字符串 如：使用file_get_contents函数读取本地的html文件
        $mail->Body = $content;

        //为该邮件添加附件 该方法也有两个参数 第一个参数为附件存放的目录（相对目录、或绝对目录均可） 第二参数为在邮件附件中该附件的名称
        // $mail->addAttachment('./d.jpg','mm.jpg');
        //同样该方法可以多次调用 上传多个附件
        // $mail->addAttachment('./Jlib-1.1.0.js','Jlib.js');

        $status = $mail->send();

        //简单的判断与提示信息
        if($status) {
            return true;
        }else{
            return false;
        }
    }

}

