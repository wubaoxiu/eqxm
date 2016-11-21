<?php 

namespace Home\Controller;
use Think\Controller;

/**
    *前台贴吧控制器 BarController
    *控制器作用    某个贴吧首页的遍历
    *@作者    yjx
    *@date    2016/11/21
*/

class BarController extends Controller
{
    // 定义数据库操作类
    private $_barinfo = null;
    private $_note = null;
    private $_user = null;

    // 初始化
    public function _initialize()
    {
        $this->_barinfo = M('barinfo');
        $this->_note = M('note');
        $this->_user = M('user');
    }

    // 主页
    public function index()
    {
        $id = 1;
        $data = $this->_barinfo->where(array('id'=>array('eq',$id)))->find();
        // dump($data);
        if(empty($data)){
            U('index/index');exit;
        }
        $list = $this->_note->where(array('bar_id'=>array('eq',$id)))->order('id desc')->select();
        foreach ($list as $k=>$v) {
            // dump($v);
            $list[$k]['louzhu'] = $this->_user->where(array('id'=>array('eq',$v['user_id'])))->find();
        }
        // dump($list);die;
        $this->assign('list',$list);
        $this->assign('louzhu',$louzhu);
        $this->assign('data',$data);
        $this->display();
    }
    public function note()
    {
        $this->display();
    }
}



