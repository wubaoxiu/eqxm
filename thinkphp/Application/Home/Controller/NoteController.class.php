<?php 
namespace Home\Controller;
use Think\Controller;
/**
    *前台贴吧控制器 LoginController
    *控制器作用    贴吧帖子的操作
    *@作者    yjx
    *@date    2016/11/21
*/
class NoteController extends Controller
{
    // 定义数据库操作类
    private $_note = null;

    // 初始化
    public function _initialize()
    {
        $this->_note = M('note');
    }

    // 帖子添加
    public function add()
    {
        session_start();
        date_default_timezone_set("PRC");
        if(empty($_SESSION['user'])){
            $this->error('您还没有登录，请先登录！',U('Login/index'));
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
    
}


