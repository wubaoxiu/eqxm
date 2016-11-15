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
        $data = $this->_role->order('id desc')->select();
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
            $this->error('修改失败!',U('Role/edit',array('id'=>$_POST['id'])));
        }
    }
}




