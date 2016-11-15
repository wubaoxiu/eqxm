<?php
namespace Admin\Controller;

  /*
  *后台管理员管理
   * @author xiao
  *date 2016-11-14
  */ 

class AdminUserController extends AdminController
{
    /*
     *获取后台管理员信息
     */ 
    public function index(){
      //查询数据
      $list = M('admin')->select();
      // var_dump($list);
      //定义一个空数组
      $arr = array();
      //遍历$list
      foreach($list as $v){
        $role_ids = M('user_role')->field('role_id')->where(array('user_id'=>array('eq',$v['id'])))->select();
        // 定义一个空数组
        $roles = array();
        //遍历
        foreach ($role_ids as $value) {
          $roles[] = M('role')->where(array('id'=>array('eq',$value['role_id'])))->getField('role');
        }
        $v['role']=$roles;
        $arr[]=$v;
      }
      $this->assign('title','用户管理');
      $this->assign('stitle','后台管理用户列表');
      $this->assign('list',$arr);
      $this->display('AdminUser/index');
    }

    /**
    *  查询后台管理员
    */ 

    public function select(){
      //获取id
      $id = I('get.id/d');
      $data = M('admin')->find($id);

      $this->assign('title','后台管理用户列表');
      $this->assign('stitle','个人信息');
      $this->assign('data',$data);
      $this->display('AdminUser/select');
    }

    /*
     *执行删除
     */ 
     public function del(){
        //判断有无ID
        if (empty($_GET['id'])) {
           $this->redirect('Admin/AdminUser/index');
           exit;
        }

        //进行数据过滤
        $id=I('get.id/d');
        if (M('admin')->delete($id)>0) {
            $this->success('恭喜您，删除成功！',U('index'));
        }else{
            $this->error('删除失败！');
        }
     }

    /*
    编辑页面
    */ 
    public function edit(){
      $rolelist = M('role')->select();
         //接受ID
        $id = I('get.id/d');
        //数据查找
        $data = M('admin')->find($id);

       //分配数据
        $this->assign('stitle','用户编辑');
        $this->assign('submit','提交');
        $this->assign('data',$data);
        $this->assign('rolelist',$rolelist);
        $this->display('AdminUser/edit');
    }

    /*
      执行编辑
    */
      public  function update(){
        if (empty($_POST)) {
            $this->redirect('Admin/AdminUser/index');
            exit;

            //数据验证 也就是数据过滤
            M('admin')->create();
            if (M('admin')->save() > 0) {
                $this->success('恭喜您，修改成功！',U('index'));
            }else{
                $this->error('修改失败！');
            }
        }
      }

       /**
       * 分配角色
       */ 

       public function rolelist(){
        //查询节点信息
        $list = M('role')->select();

        //查询当前用户的信息
        $admin = M('admin')->where(array('id'=>array('eq',I('id'))))->find();

        //查询当前用户的角色信息
        $role = M('user_role')->where(array('user_id'=>array('eq',I('id'))))->select();

        //定义一个空数组
        $myrole = array();

        //对角色进行重组
        foreach ($role as $v) {
          $myrole[]=$v['role_id'];
        }
        $this->assign('title','后台管理员列表');
        $this->assign('stitle','分配角色');
        //分配数据
        $this->assign('list',$list);
        $this->assign('admin',$admin);
        $this->assign('role',$myrole);
        $this->display('AdminUser/rolelist');
       }

       /**
       *  保存角色信息
       *
       */ 

       public function saverole(){
        //判断必须选择一个角色
        if (empty($_POST['role'])) {
          $this->role('必须选择一个角色');
        }
        //获取id
        $uid = $_POST['user_id'];
        // var_dump($uid);

        //清除原有的角色 以免重复
        M('user_role')->where(array('user_id'=>array('eq',$uid)))->delete();

        foreach (I('role') as $v) {
          $data['user_id']=$uid;
          $data['role_id']=$v;
        M('user_role')->data($data)->add();
        }
       $this->success('恭喜您，角色修改成功',U('AdminUser/index'));
       }
}