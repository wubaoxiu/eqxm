<?php
namespace Admin\Controller;

class UserController extends AdminController
{

    /*
        获取普通用户列表
    */

    public function index(){
        //查询数据
        $list = M('user')->select();
        //定义一个空数组
        $arr = array(); 
        //声明遍历信息
        foreach ($list as $v) {
            $role_ids = M('user_role')->field('role_id')->where(array('user_id'=>array('eq',$v['id'])))->select();
          //定义一个空数组
            $roles = array();
            //遍历
            foreach ($role_ids as $value) {
                $roles[]=M('role')->where(array('id'=>array('eq',$value['role_id'])))->getField('role');
            }
            $v['role']=$roles;
            //将新得到的角色信息放置到$vz中
            $arr[]=$v;
        }
        // var_dump($list);
        // var_dump($arr);

        //
        $this->assign('list',$arr);
        $this->assign('title','普通用户列表');
        $this->display('User/index');
    }
    /*
      查询用户个人信息
    */ 
      public function select(){
        $rolelist = M('role')->select();
        $data = M('user')->find($id);
        $this->assign('title','个人信息');
        $this->assign('data',$data);
        $this->assign('rolelist',$rolelist);
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
        if (M('user')->delete($id)>0) {
            $this->success('恭喜您，删除成功！',U('User/index'));
        }else{
            $this->error('删除失败.....');
        }
    }

    /*
        获取添加用户页面
    */

    public function add(){
        $data = M('role')->select();
        // var_dump($data);
        $this->assign('title','添加普通用户列表');
        $this->assign('data',$data);

        $this->display('User/add');
    }
    /*
    执行添加操作
    */ 
    public function doAdd(){
        //得到数据模型 
        $user = D('user');
        $role_id=I('post.id/d');
        //进行数据过滤 也就是数据验证
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

    /*
     编辑页面
    */ 
     public function edit(){
        $rolelist = M('role')->select();
        //获取ID
        $id = I('get.id/d');
        //查出数据
         $data = M('user')->find($id);
         $this->assign('title','编辑普通用户列表');
         $this->assign('data',$data);
         $this->assign('rolelist',$rolelist);
         $this->display('User/edit');

     }

     /*
     执行编辑
     */ 
     public function update()
     {
        $user=M('user');
        // 判断有无值
        if (empty($_POST)) {
            $this->redirect('Admin/User/index');
            exit;
        }
        //数据过滤
         $a = $user->create();
         // var_dump($a);exit;
        //执行修改
         if ($user->save()>0) {
                $this->success('恭喜您，修改成功',U('User/index'));
            }else{
                $this->error('修改失败.....');
            }
    }

    /*
     分配、浏览角色（权限）
    */ 
     public function rolelist(){
        //查询节点信息
        $list = M('role')->select();
        //查询用户信息
        $user= M('user')->where(array('id'=>array('eq',I('id'))))->find();
        //获取当前用户的角色信息
        $rolelist = M('user_role')->where(array('user_id'=>array('eq',I('id'))))->select();
        // var_dump($rolelist);
        //定义一个空数组
        $myrole = array();

        //对用户角色进行重组
        foreach ($rolelist as $v) {
           $myrole[]=$v['role_id'];
        }
        // var_dump($myrole);
        $this->assign('title','分配角色');
        //分配数据
        $this->assign('list',$list);
        //分配当前用户信息
        $this->assign('user',$user);
        //分配该用户的角色信息
        $this->assign('role',$myrole);
        //加载模板
        $this->display('User/rolelist');
     }

     /*
     保存用户角色
     */ 
     public function saverole(){
        //判断必须选择一个角色
        if (empty($_POST['role'])) {
           $this->error("请选择一个角色");
        }
        $uid = $_POST['user_id'];
        //清除用户所有的角色信息，避免重复
        M('user_role')->where(array('user_id'=>array('eq',$uid)))->delete();
        // $list = I('role');
        foreach (I('role') as $v) {
            $data['user_id']=$uid;
            $data['role_id']=$v;
            //执行添加
            M('user_role')->data($data)->add();
        }
        $this->success('角色分配成功',U('User/index'));
     }
 
}