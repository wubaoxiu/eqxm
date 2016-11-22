<?php 


namespace Home\Controller;
use Think\Controller;


   /**
   * 个人资料查询
   * @author xiao
   * date 2016-11-22
   */ 
class SetController extends Controller{

    public function index(){
        //获取id
        $id = $_SESSION['user']['id'];
        //查找该用户的信息
        $list = M('user')->find($id);
        // var_dump($id);
        //分配数据
        $this->assign('list',$list);
        $this->display();
    }
}