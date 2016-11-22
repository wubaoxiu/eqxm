<?php 


namespace Home\Controller;
use Think\Controller;

    /**
    * 我的主页
    * @author xiao
    * date 2016-11-19
    */ 
    class MyInfoController extends Controller{ 

        public function index()
        {
            $id = $_SESSION['user']['id'];
          $list = M('user')->find($id);
          // $data = $this->_barinfo->find($id);
          // var_dump($list);
          $this->assign('list',$list);
           $this->display();

        }
    }
