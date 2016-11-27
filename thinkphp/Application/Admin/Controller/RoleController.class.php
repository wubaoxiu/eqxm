<?php 
namespace Admin\Controller;

    /**
    *   控制器名称 RoleController
    *   控制器作用 对数据库角色表的增删改查
    *   @author     yjx
    *   @date 2016/11/14
    */

class RoleController extends AdminController
{
    private $_role = null;// 角色表数据库对象
    private $_node = null;// 节点表数据库对象
    private $_role_node = null;// 角色--权限表数据库对象
    private $_user_role = null;// 用户--角色表数据库对象

    // 初始化所有数据库对象
    public function _initialize()
    {
        parent::_initialize();// 调用父类的方法
        $this->_role = D('role');
        $this->_node = D('node');
        $this->_role_node = M('role_node');
        $this->_user_role = M('user_role');
    }

    // 角色首页遍历
    public function index()
    {
        $data = array();
        $data = $this->_role->order('id desc')->where(array('id'=>array('gt',1)))->select();
        $this->assign('title','角色管理');
        $this->assign('stitle','角色列表');
        $this->assign('rolelist',$data);
        $this->display('Role/index');
    }

    // 角色删除
    public function del()
    {
        $id = $_GET['id'];
        // echo $id;
        if($this->_role->delete($id) > 0 && $this->_role_node->where(array('role_id'=>array('eq',$id)))->delete() >0 && $this->_user_role->where(array('role_id'=>array('eq',$id)))->delete() > 0) {
            $this->success('删除成功',U('Role/index'));
        } else {
            $this->error('删除失败',U('Role/index'));
        }
    }

    // 角色添加页面
    public function add()
    {
        $this->assign('title','角色管理');
        $this->assign('stitle','角色添加');
        $this->display('Role/add');
    }

    // 执行角色添加
    public function doAdd()
    {
        if(!$this->_role->create()){
            $this->error($this->_role->getError());
            // echo $this->_role->getLastSql();
            exit;
        }
        if($this->_role->add()>0){
            $this->success('添加成功',U('Role/index'));
        } else {
            $this->error('添加失败',U('Role/add'));
        }
    }

    // 跳到编辑角色页面
    public function edit()
    {
        $id = I('get.id/d');
        $data = array();
        $data = $this->_role->find($id);
        $this->assign('crole',$data);
        $this->assign('title','角色管理');
        $this->assign('stitle','角色编辑');
        $this->display('Role/edit');
    }

    // 执行修改操作
    public function save()
    {
        if(!$this->_role->create()){
            $this->error($this->_role->getError());
            exit;
        } 
        if($this->_role->save()>0){
            $this->success('修改成功！',U('Role/index'));
        } else {
            $this->error('修改失败!');
        }
    }

    // 查看角色拥有权限页面，可修改提交
    public function nodelist()
    {
        // 获取角色信息
        $id = I('get.id/d');
        $role = $this->_role->find($id);
        // 获取该角色的权限
        $role_node = $this->_role_node->where(array('role_id'=>array('eq',$id)))->select();
        // 获取所有的权限
        $nodes = $this->_node->select();
        // 遍历角色权限组成新的数组
        $new_role_node = array();
        foreach ($role_node as $v) {
            $new_role_node[] = $v['node_id'];
        }

        // 向模板分配该角色拥有的权限
        $this->assign('role_node',$new_role_node);
        // 向模板分配所有的节点信息
        $this->assign('nodes',$nodes);
        // 向模板分配角色信息
        $this->assign('role',$role);

        $this->assign('title','角色列表');
        $this->assign('stitle','节点分配');
        // 加载模板
        $this->display('Role/nodelist');
    }

    // 修改角色节点
    public function savenode()
    {
        // var_dump($_POST);
        // 接受参数
        $node = $_POST['node'];
        $id = $_POST['role_id'];
        // 节点数量不能为零
        if(empty($node)){
            $this->error('至少分配一个权限!');
            exit;
        }
        // 清空该角色所有的权限
        $this->_role_node->where(array('role_id'=>array('eq',$id)))->delete();
        // echo $this->_role_node->getLastSql();
        // exit;
        // 遍历角色新分配的权限,生成数据并添加
        // $data = array();
        // foreach ($node as $v) {
        //     $data['role_id'] = $id;
        //     $data['node_id'] = $v;
        //     // var_dump($data);
        //     // 往_role_node表中循环添加数据,data方法直接生成要操作的数据对象
        //     // var_dump($this->_role_node->data($data));
        //     // $this->_role_node->field('role_id,node_id')->create($data)->add();
        //     $this->_role_node->data($data)->add();
        // }
        foreach ($node as $v) {
            $this->_role_node->role_id = $id;
            $this->_role_node->node_id = $v;
            $this->_role_node->add();
        }

        $this->success('分配成功！',U('Role/index'));
    }
}




