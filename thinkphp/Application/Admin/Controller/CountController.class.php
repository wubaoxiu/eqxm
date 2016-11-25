<?php 

namespace Admin\Controller;

/**
* 控制器名：CountController
*     控制器作用：统计管理
* @author xiao
* @date  2016-11-25
*/
class CountController extends AdminController
{
   public function index(){

   	$adminCount = M('admin')->count();
   	$homeCount = M('user')->count();
   	$barCount = M('barinfo')->count();
   	$noteCount = M('note')->count();
   	$this->assign('adminCount',$adminCount);
   	$this->assign('homeCount',$homeCount);
   	$this->assign('barCount',$barCount);
   	$this->assign('noteCount',$noteCount);
   	$this->display();
   }

}