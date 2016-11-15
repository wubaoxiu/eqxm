<?php 
namespace Admin\Controller;
/**
*   控制器名称 NodeController
*   控制器作用 对数据库节点表的增删改查
*   @author    yjx
*   @date      2016/11/14
*/ 

class NodeController extends AdminController
{
    private $_node = null;// 节点表数据库对象

    // 初始化数据库对象
    public function _initialize()
    {
        parent::_initialize();
        $this->_node = D('node');// 实例化node
        // var_dump($this->_node);
    }

    // 节点首页的遍历
    public function index()
    {
        $data = array();
        $data = $this->_node->order('id desc')->select();
        $this->assign('title','节点管理');
        $this->assign('stitle','节点列表');
        $this->assign('nodelist',$data);
        $this->display('Node/index');
    } 

    // 跳转到添加页面
    public function add()
    {
        $this->assign('title','节点管理');
        $this->assign('stitle','节点添加');
        $this->display('Node/add');
    }

    // 添加节点
    public function doAdd()
    {
        if(!$this->_node->create()){
            $this->error($this->_node->getError());
            exit;
        }
        if($this->_node->add() > 0){
            $this->success('节点添加成功',U('Node/index'));
        } else {
            $this->success('节点添加失败',U('Node/add'));
        }
    }
}




