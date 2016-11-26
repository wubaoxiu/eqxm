<?php 

namespace Admin\Controller;

    /** 
    *  用户反馈管理
    * @author xiao 
    * date 2016-11-18
    */ 
class FeedbackController extends AdminController{
    private $_feenback = null;//用户反馈操作表
    private $_user = null;//用户反馈操作表
    private $_feedback_reply = null;//回复操作表


    /**
    * 方法名 _initialize()  初始化操作
    * @return[void]
    */
    public function _initialize(){
        parent::_initialize();
        $this->_feedback = M('feedback');
        $this->_user = M('user');
        $this->_feedback_reply = M('feedback_reply');
    } 

    public  function index(){
        $list = $this->_feedback->select();
        $this->assign('title','用户意见与反馈');
        $this->assign('stitle','意见与反馈列表');
        $this->assign('list',$list);
        $this->display('Feedback/index');
    }

    //查看回复页面
    public function reply(){
        $id = I('get.id/d');
         $list = $this->_feedback->where("id = '$id'")->select();
        $this->assign('title','用户意见反馈');
        $this->assign('stitle','查询并回复用户');
        $this->assign('list',$list);
        $this->display('Feedback/feedback_reply');
    }

    public function doReply(){
        // var_dump($_POST);die;
       $post = $_POST;
       $post['reply_time']=time();
       // var_dump($post);die;
        if ($this->_feedback_reply->data($post)->add()>0) {
            $this->success('回复成功！',U('Feedback/index'));
        }else{
            $this->error('回复失败.....');
        }


    }


}