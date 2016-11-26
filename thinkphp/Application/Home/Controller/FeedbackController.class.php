<?php 

namespace Home\Controller;

use Think\Controller;

/**
 *  控制器名：FeedbackController
 *      意见反馈
 *  @author xiao
 *  @date 2016-11-26
*/
class FeedbackController extends Controller
{
	private $_user = null;//用户表操作
	private $_feedback = null;//意见反馈表操作

	public function _initialize(){
		$this->_user = M('user');
		$this->_feedback = M('Feedback');
	 }
   public function index(){
	   	$id = I('get.uid/d');
	   	$list = $this->_user->field('name,hpic')->where(array('id'=>array('eq',$id)))->select();
	   	  //意见反馈
         $feedback = M('feedback')->field('f.add_time,f.content fcontent,fr.content,fr.reply_time')->where('f.id=fr.feedback_id')->table('csw_feedback f,csw_feedback_reply fr')->select();
         var_dump($feedback);
	   	// var_dump($list);
	   	$this->assign('feedback',$feedback);
	   	$this->assign('list',$list);
	   	
	   	
	   	$this->display();
	   }

   //处理反馈内容
	public function dofeedback(){
		$arr = [];
		// $arr['user_id']=$_POST['id'];
		$arr['name']=$_POST['name'];
		$arr['content']=$_POST['content'];
		$arr['add_time']=time();
		var_dump($arr);
		$res = $this->_feedback->data($arr)->add();
		if ($res) {
			$this->success('恭喜您.提交成功',U('Index/index'));
		}else{
			$this->error('提交失败......');
		}

	}


}
