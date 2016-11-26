<?php 
namespace Home\Controller;
use Think\Controller;

class UserController extends ConmonController
{

    public function index()
    {
        //实例化model模型
        // $model = new \Think\Model('user');
        $model = M('user');
        // var_dump($model);
        $data = $model->order('id desc')->select();
        // var_dump($data);
        //发送数据
        $this->assign('list',$data);
        //加载模版
        $this->display();
    }
}



