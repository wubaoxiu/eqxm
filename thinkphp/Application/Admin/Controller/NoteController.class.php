<?php 
namespace Admin\Controller;

class NoteController extends AdminController
{
    private $_note = null;//帖子表操作
    private $_user = null;//用户表表操作
    private $_barinfo = null;//贴吧表操作

    public function _initialize(){
        parent::_initialize();
        $this->_note = D('Note');
        $this->_user = D('User');
        $this->_barinfo = D('Barinfo');
    }
    //帖子用户列表
    public function index(){
        //查询数据
        $list = $this->_note->select();
       // $data = $this->_user->field('u.name')->table('csw_user u,csw_note n')->where('u.id=n.user_id')->select();
        //声明一个空数组
        $arr = array();
        //遍历帖子信息
        foreach ($list as $v) {
           $notes = $this->_user->field('user_id')->where(array('id'=>array('eq',$v['user_id'])))->select();
           var_dump($notes);
        }

        $this->assign('title','帖子列表');
        //分配数据
        $this->assign('data',$data);
        $this->assign('list',$list);
        $this->display('Note/index');
    }


   //帖子用户管理
    public function select(){
        //获取当前用户的id
        $id = I('get.id/d');
        $list = $this->_note->find($id);
        $data = $this->_user->field('u.hpic')->table('csw_user u,csw_note n')->where('u.id=n.user_id')->select();
        // var_dump($data);die;
        $this->assign('title','帖子个人信息查询');
        $this->assign('list',$list);
        $this->assign('data',$data);
        $this->display('Note/select');
    }

    //帖子删除
    public function del(){

        //判断有无ID
        if (empty($_GET['id'])) {
            $this->redirect('Admin/Note/index');
            exit;
        }

        //过滤 也就是数据验证
        $id = I('get.id/d');
        if ($this->_note->delete($id)>0) {
            $this->success('恭喜您，删除成功',U('Note/index'));
        }else{
            $this->error('删除失败......');
        }

    }

    /**
    *  获取修改是否置顶 是否加精页面
    */ 
    public function edit(){
        //获取id
        $id = I('get.id/d');
        $data = $this->_note->field('id,istop,isfine')->find($id);
        // var_dump($data);die;
        //分配数据
        $this->assign('title','帖子用户列表');
        $this->assign('stitle','编辑列表');
        $this->assign('data',$data);
        $this->display('Note/edit');
    }


   /**
   *  执行编辑
   */ 
   public  function save(){
     //判断post是否为空
    if (empty($_POST)) {
        $this->redirect('Admin/Note/index');
        exit;
    }

    // $this->_note->create();
    $data['id'] = $_POST['id'];
    $data['istop'] = $_POST['istop'];
    $data['isfine'] = $_POST['isfine'];
    // var_dump($data);die;

    if ($this->_note->save($data)>0) {
        $this->success('恭喜你，修改成功',U('Note/index'));
    }else{
        $this->error('修改失败......');
    }

   }
}