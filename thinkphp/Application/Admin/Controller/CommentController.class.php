<?php
namespace Admin\Controller;

/**
* 控制器名：BarinfoController
*     控制器作用：贴吧管理
* @author wbx
* @date  2016-11-17
*/
class CommentController extends AdminController
{
    private $_comment = null;
    private $_barinfo = null;
    private $_user = null;

    /**
    * 方法名 _initialize()  初始化操作
    * @return[void]
    */
    public function _initialize()
    {
        parent::_initialize();
        $this->_comment = M('comments');
        $this->_barinfo = M('_barinfo');
        $this->_user = M('user');
    }

    /**
    * 方法名 index() 获取评论列表
    *
    * @return[void]
    */
    public function index(){
        $data = $this->_comment->field('c.id,u.name uname,c.note_id,c.floor,c.status,b.name bname,c.createtime')->table("csw_user u,csw_barinfo b,csw_comments c,csw_note n")->where('c.user_id=u.id and c.note_id=n.id and n.bar_id=b.id')->select();
        // dump($data);

        $this->assign('title',"评论列表");
        $this->assign('list',$data);

        $this->display("Comment/index");
    }

    public function changeStatus(){
        $id = I('get.id');
        $status= $this->_comment->where(array('id'=>array('eq',$id)))->getField('status');
        // dump($status);exit;
        if($status == 1){
            $data['status']=2;
            $this->_comment->where(array('id'=>array('eq',$id)))->save($data);
            $this->ajaxReturn(2);
        }else if($status == 2){
            $data['status']=1;
            $this->_comment->where(array('id'=>array('eq',$id)))->save($data);
            $this->ajaxReturn(1);
        }   
    }

    public function detail()
    {
        $id = I('id');
        if(empty($id)){
            $this->error("操作错误！！！");
            exit;
        }
        $data = $this->_comment->field('content')->where(array('id'=>array('eq',$id)))->find();
        // dump($data);

        $this->assign('title','评论详情');
        $this->assign('list',$data);

        $this->display('Comment/detail');
    }
}