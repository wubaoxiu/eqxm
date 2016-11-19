<?php 
namespace Admin\Controller;

        /**
        * 帖子管理
        * @author xiao
        * date 2016-11-16
        */ 

class NoteController extends AdminController
{
    private $_note = null;//帖子表操作
    private $_user = null;//用户表表操作
    private $_barinfo = null;//贴吧表操作


  
    /**
    * 方法名 _initialize()  初始化操作
    * @return[void]
    */
    public function _initialize(){
        parent::_initialize();
        $this->_note = D('Note');
        $this->_user = D('User');
        $this->_barinfo = D('Barinfo');
    }


    //帖子用户列表
    public function index(){

    //查询数据
      $list = $this->_note->field('n.id,n.title,u.name uname,n.istop,n.isfine,b.name bname')->table('csw_user u,csw_note n,csw_barinfo b')->where('n.bar_id=b.id and n.user_id = u.id')->select();
        $this->assign('title','帖子管理');
        $this->assign('stitle','帖子列表');
        //分配数据
        $this->assign('list',$list);
        $this->display('Note/index');
    }


   //帖子用户查询
    public function select(){
        //获取当前用户的id
        $id = I('get.id/d');
       //查询数据
         $list = $this->_note->field('n.id,n.title,n.scan,n.reply,n.content,u.name uname,u.hpic,n.istop,n.isfine,b.name bname')->table('csw_user u,csw_note n,csw_barinfo b')->where("n.bar_id=b.id and n.user_id = u.id and n.id='$id'")->select();
    
        foreach ($list as $value) {
           $arr = $value;
        }
        // var_dump($arr);
        $this->assign('title','帖子列表');
        $this->assign('stitle','帖子信息');
        //分配数据
        $this->assign('data',$arr);
        $this->display('Note/select');
    }

    // //帖子删除
    // public function del(){

    //     //判断有无ID
    //     if (empty($_GET['id'])) {
    //         $this->redirect('Admin/Note/index');
    //         exit;
    //     }

    //     //过滤 也就是数据验证
    //     $id = I('get.id/d');
    //     if ($this->_note->delete($id)>0) {
    //         $this->success('恭喜您，删除成功',U('Note/index'));
    //     }else{
    //         $this->error('删除失败......');
    //     }

    // }

    /**
    *  获取修改是否置顶 是否加精页面
    */ 
    public function edit(){
        //获取id
        $id = I('get.id/d');
        $data = $this->_note->field('id,istop,isfine,is_show')->find($id);
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
    $data['id'] = $_POST['id'];
    $data['istop'] = $_POST['istop'];
    $data['isfine'] = $_POST['isfine'];
    $data['is_show'] = $_POST['is_show'];
    if ($this->_note->save($data) !==false) {
        $this->success('恭喜你，修改成功',U('Note/index'));
    }else{
        $this->error('修改失败......');
    }

   }
}