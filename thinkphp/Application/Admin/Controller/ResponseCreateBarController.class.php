<?php
namespace Admin\Controller;

  /**
   * 后台管理员管理
   *  @author xiao
   * date 2016-11-14
  */ 

class ResponseCreateBarController extends AdminController
{
    public function createBarList()
    {
        $data = M('requestcreatebar')->select();
        // echo "111";
        dump($data);

        $this->assign('list',$data);
        $this->display("Barinfo/createBarList");
    }
}