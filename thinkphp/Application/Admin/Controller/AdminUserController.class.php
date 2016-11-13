<?php
namespace Admin\Controller;

class AdminUserController extends AdminController
{
    // public function adminUserAdmin(){
    //     $this->display('');
    // }
    /*
    获取后台管理员信息
    */ 
    public function index(){
      $data = M('admin')->field('a.id,a.name,a.sex,a.email,r.role')->table('csw_admin a,csw_role r')->where('a.role_id=r.id')->select();
      $this->assign('title','后台管理用户列表');
      $this->assign('Adminlist',$data);
      $this->display('AdminUser/index');
    }

    /*
     执行删除
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
   添加页面
   */ 
    public function add(){
     $this->assign('title','后台用户添加');
     $this->display('User/add');
    }

    /*
    执行添加
    */ 
    public function doAdd(){
        //得到数据模型
        $adminUser = M('admin');
        //进行数据验证
        if (!$adminUser->create()) {
            //如果创建失败 则表示没有通过
            //输出错误信息，并进行挑战
            $this->error($adminUser->getError());
        }else{
            //验证通过，执行添加操作
            if (D('admin')->add()>0) {
                $this->success('恭喜您，添加成功！',U('index'));
            }else{
                $this->error('添加失败！');
            }
        }
    }

    /*
    编辑页面
    */ 
    public function edit(){
         //接受ID
        $id = I('get.id/d');
        //数据查找
        $data = M('admin')->find($id);

       //分配数据
        $this->assign('title','用户编辑');
        $this->assign('submit','提交');
        $this->assign('data',$data);
        $this->display('AdminUser/edit');
    }

    /*
      执行编辑
    */
      public  function save(){
        if (!empty($_POST)) {
            $this->redirect('Admin/AdminUser/index');
            exit;

            //数据验证 也就是数据过滤
            M('admin')->create();
            if (M('admin')->update()) {
                $this->success('恭喜您，修改成功！',U('index'));
            }else{
                $this->error('修改失败！');
            }
        }
      } 
}