<?php
namespace Admin\Controller;


        /**
        *  普通用户管理
        * @author xiao
        * date 2016-11-13
        */ 

class UserController extends AdminController
{

    private $_user = null; //前台用户操作表
    private $_user_role = null; //用户角色操作表
    private $_role = null; //角色操作表
    private $_baradmin = null; //吧管理操作表

    /**
    * 方法名 _initialize()  初始化操作
    * @return[void]
    */
    public  function _initialize(){
        parent::_initialize();
        $this->_user = D('user');
        $this->_user_role = D('user_role');
        $this->_baradmin = M('baradmin');
        $this->_role = M('role');
    }

    /*
        获取普通用户列表
    */

    public function index(){
        //查询数据
        $list = $this->_user->select();
        $this->assign('list',$list);
        $this->assign('title','用户管理');
        $this->assign('stitle','普通用户列表');
        $this->display('User/index');
    }


        /*
          查询用户个人信息
        */ 

      public function select(){
         //获取ID
        $id = I('get.id/d');
        // var_dump($id);
        $list = $this->_baradmin->field('status')->where(array('user_id'=>array('eq',$id)))->select();
         $role_id = $this->_user_role->field('role_id')->where(array('user_id'=>array('eq',$id)))->select();
         $adminrole = $this->_role->where(array('id'=>$role_id[0]['role_id']))->select();
        // var_dump($list);
        $data = $this->_user->find($id);
        $this->assign('title','前台用户列表');
        $this->assign('stitle','个人信息');
        $this->assign('data',$data);
        $this->assign('list',$list);
        $this->assign('adminrole',$adminrole);
        $this->display('User/select');
      }

        /*
        执行删除
        */ 

    public function del(){
        //判断有无ID
        if (empty($_GET['id'])) {
            $this->redirect('Admin/User/index');
            exit;
        }
        //进行数据验证 （过滤）
        $id=I('get.id/d');
        if ($this->_user->delete($id)>0) {
            $this->success('恭喜您，删除成功！',U('User/index'));
        }else{
            $this->error('删除失败.....');
        }
    }

    /*
        获取添加用户页面
    */

    public function add(){
        $this->assign('stitle','添加普通用户列表');    
        $this->display('User/add');
    }
    /*
    执行添加操作
    */ 
    public function doAdd(){

        $hpic = $this->upload();
        $_POST['hpic'] = $hpic;
        //得到数据模型 
        $user = $this->_user;
        //进行数据过滤 也就是数据验证
        $role_id=I('post.id/d');
        if (!$user->create()) {
            //如果创建没有完成，则验证失败
            //输出错误信息 并跳转
            $this->error($user->getError());
            exit;
        }else{
            //验证成功，进行添加
            if ($user->add()>0) {
                $this->success('恭喜您，添加成功',U('User/index'));
            }else{
                $this->error('添加失败.....');
            }
        }
    }

     //处理头像
    public function updateAvator(){
         $hpic = $this->upload();
        $data['hpic'] = $hpic;
        $data['id'] = I('get.id/d');
       $this->_user->save($data);
       return $this->ajaxReturn($hpic);

    } 

    // 图片上传
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
     * 获取编辑页面
    */ 

     public function edit(){
        //获取ID
        $id = I('get.id/d');
        //查出数据
         $data = $this->_user->find($id);
         $this->assign('stitle','编辑普通用户列表');
         $this->assign('data',$data);
         $this->display('User/edit');

     }

     /*
     执行编辑
     */ 
     public function save()
     { 

        $user=$this->_user;
        // 判断有无值
        if (empty($_POST)) {
            $this->redirect('Admin/User/index');
            exit;
        }  
        //数据过滤
         $user->create();
         $data['id']=$_POST['id'];
         $data['name']=$_POST['name'];
         $data['email']=$_POST['email'];
         $data['status']=$_POST['status'];
         $data['sex']=$_POST['sex'];
        //执行修改
         if ($user->save($data) !==false) {
                $this->success('恭喜您，修改成功',U('User/index'));
            }else{
                $this->error('修改失败.....');
            }
    }



   
    // /*
    //  分配、浏览角色（权限）
    // */ 
    //  public function rolelist(){
    //     //查询节点信息
    //     $list = M('role')->select();
    //     //查询用户信息
    //     $user= M('user')->where(array('id'=>array('eq',I('id'))))->find();
    //     //获取当前用户的角色信息
    //     $rolelist = M('user_role')->where(array('user_id'=>array('eq',I('id'))))->select();
    //     // var_dump($rolelist);
    //     //定义一个空数组
    //     $myrole = array();

    //     //对用户角色进行重组
    //     foreach ($rolelist as $v) {
    //        $myrole[]=$v['role_id'];
    //     }
    //     // var_dump($myrole);
    //     $this->assign('title','分配角色');
    //     //分配数据
    //     $this->assign('list',$list);
    //     //分配当前用户信息
    //     $this->assign('user',$user);
    //     //分配该用户的角色信息
    //     $this->assign('role',$myrole);
    //     //加载模板
    //     $this->display('User/rolelist');
    //  }

    //  /*
    //  保存用户角色
    //  */ 
    //  public function saverole(){
    //     //判断必须选择一个角色
    //     if (empty($_POST['role'])) {
    //        $this->error("请选择一个角色");
    //     }
    //     $uid = $_POST['user_id'];
    //     //清除用户所有的角色信息，避免重复
    //     M('user_role')->where(array('user_id'=>array('eq',$uid)))->delete();
    //     // $list = I('role');
    //     foreach (I('role') as $v) {
    //         $data['user_id']=$uid;
    //         $data['role_id']=$v;
    //         //执行添加
    //         M('user_role')->data($data)->add();
    //     }
    //     $this->success('角色分配成功',U('User/index'));
    //  }
 
}