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
      $role_id = M('user_role')->field('role_id')->where(array('user_id'=>array('eq',$id)))->select();
      $adminrole = M('role')->where(array('id'=>$role_id[0]['role_id']))->select();

      $this->assign('title','后台管理用户列表');
      $this->assign('stitle','个人信息');
      $this->assign('data',$data);
      $this->assign('adminrole',$adminrole);
      $this->display('AdminUser/select');
    }

    /**
    *  获取添加后台管理员页面
    */ 
    public function add(){
    $data = M('role')->select();
     $this->assign('title','后台用户列表');
     $this->assign('stitle','添加后台用户');
     $this->assign('data',$data);
     $this->display('AdminUser/add');
    }

    /**
    *  执行添加
    */ 

    public function doAdd(){
      $data['name']=$_POST['name'];
      $data['password']=md5($_POST['password']);
      $data['repassword']=md5($_POST['repassword']);
      $data['email']=$_POST['email'];
      $data['sex']=$_POST['sex'];
      $hpic = $this->upload();
      $data['hpic']=$hpic; 
     //得到数据模型
      $admin = M('admin');
      //获取id
      $id = I('post.id/d'); 

     $admin_id = $admin->add($data);
       if ($admin_id>0) {
          $list['user_id']=$admin_id;
          $list['role_id']=$_POST['role_id'];
            M('user_role')->add($list);
          $this->success('恭喜您，添加成功',U('index'));
        }else{
          $this->error('删除失败......');
        }

      }


     //处理头像
    public function updateAvator(){
         $hpic = $this->upload();
        $data['hpic'] = $hpic;
        $data['id'] = I('get.id');
       M('admin')->save($data);
       return $this->ajaxReturn($hpic);

    } 


    /**
    *  图片上传
    */ 

   public function upload(){
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize = 3145728 ;// 设置附件上传大小
        $upload->exts = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath = 'Public/linkimg/'; // 设置附件上传根目录
        $upload->savePath = ''; // 设置附件上传（子）目录
        // 上传文件
        $info = $upload->upload();
        if(!$info) {// 上传错误提示错误信息
            $this->error($upload->getError());
        }else{
            //获取上传后的路径和文件名
            
            $path = 'Public/linkimg/'.$info['file']['savepath'];
            $name = $info['file']['savename'];

            $big = ltrim('./'.$path.$name);
            
            $image = new \Think\Image();
            $image->open($big);
            // 生成一个缩放后填充大小150*150的缩略图并保存为thumb.jpg
            $image->thumb(150, 150,\Think\Image::IMAGE_THUMB_FILLED)->save('./'.$path.'s_'.$name);
            unlink($big);
            $small = ltrim($path.'s_'.$name);
            //$arr = array('small'=>$small,'big'=>$big);
            return $small;

        }
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
            $list['user_id']= $id;
            M('user_role')->where(array('user_id'=>array('eq',$id)))->delete();
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
         //接受ID
        $id = I('get.id/d');
        $role_id = M('user_role')->field('role_id')->where(array('user_id'=>array('eq',$id)))->find();
        $list = M('role')->field('id')->where(array('id'=>$role_id['role_id']))->select();
        //数据查找
        $data = M('admin')->find($id);
        $rolelist = M('role')->select();

           //分配数据
        $this->assign('title','后台用户列表');
        $this->assign('stitle','用户编辑');
        $this->assign('submit','提交');
        $this->assign('data',$data);
        $this->assign('list',$list);
        $this->assign('rolelist',$rolelist);
        $this->display('AdminUser/edit');
    }

    /*
      执行编辑
    */
    public  function save(){

        if (empty($_POST)) {
            $this->redirect('Admin/AdminUser/index');
            exit;
          }
            $data['id']=$_POST['id'];
            $data['name']=$_POST['name'];
            $data['email']=$_POST['email'];
            $data['sex']=$_POST['sex'];
            // var_dump($data);
                $new_role_id = $_POST['role_id'];
                $list['role_id']=$new_role_id;
                M('user_role')->where(array('user_id'=>array('eq',$_POST['id'])))->save($list);
          if (M('admin')->save($data) !== false) {
              $this->success('恭喜您，修改成功！',U('index'));
          }else{
              $this->error('修改失败！');
          }
        }

 

       /**
       * 分配角色
       */ 

       // public function rolelist(){
       //  //查询节点信息
       //  $list = M('role')->select();

       //  //查询当前用户的信息
       //  $admin = M('admin')->where(array('id'=>array('eq',I('id'))))->find();

       //  //查询当前用户的角色信息
       //  $role = M('user_role')->where(array('user_id'=>array('eq',I('id'))))->select();

       //  //定义一个空数组
       //  $myrole = array();

       //  //对角色进行重组
       //  foreach ($role as $v) {
       //    $myrole[]=$v['role_id'];
       //  }
       //  $this->assign('title','后台管理员列表');
       //  $this->assign('stitle','分配角色');
       //  //分配数据
       //  $this->assign('list',$list);
       //  $this->assign('admin',$admin);
       //  $this->assign('role',$myrole);
       //  $this->display('AdminUser/rolelist');
       // }

       // /**
       // *  保存角色信息
       // *
       // */ 

       // public function saverole(){
       //  //判断必须选择一个角色
       //  if (empty($_POST['role'])) {
       //    $this->role('必须选择一个角色');
       //  }
       //  //获取id
       //  $uid = $_POST['user_id'];
       //  // var_dump($uid);

       //  //清除原有的角色 以免重复
       //  M('user_role')->where(array('user_id'=>array('eq',$uid)))->delete();

       //  foreach (I('role') as $v) {
       //    $data['user_id']=$uid;
       //    $data['role_id']=$v;
       //  M('user_role')->data($data)->add();
       //  }
       // $this->success('恭喜您，角色修改成功',U('AdminUser/index'));
       // }
       
}