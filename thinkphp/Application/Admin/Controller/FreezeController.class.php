<?php 
namespace Admin\Controller;

    /**
    *   控制器名称 FreezeController
    *   控制器作用 对数据库封号表的增删改查
    *   @author     yjx
    *   @date 2016/11/16
    */
class FreezeController extends AdminController
{
    // 定义数据库操作类
    private $_fre = null;
    private $_user = null;
    // 初始化操作类
    public function _initialize()
    {
        parent::_initialize();
        $this->_fre = M('shutup');
        $this->_user = M('user');
    } 

    // 禁言首页遍历
    public function index()
    {
        $list = array();
        // 遍历封号信息
        foreach ($this->_fre->select() as $k => $v) {
            // var_dump($v['id']);}exit;
            // 重新生成数组，将freeze表中的用户id和贴吧id转化为用户名和贴吧名
            $list[$v['id']]['un'] = $this->_user->field('name')->where(array('id'=>array('eq',$v['user_id'])))->find();
            $list[$v['id']]['bn'] = $this->_barinfo->field('name')->where(array('id'=>array('eq',$v['bar_id'])))->find();
            // 解除禁言的时间
            $list[$v['id']]['relieve'] = $v['relieve'];
        }
        // var_dump($list);
        $this->assign('list',$list);
        $this->assign('title','禁言管理');
        $this->display();
    }

    // 解除禁言
    public function del()
    {
        $id = I('get.id/d');
        if(empty($id)){
            $this->error('没有可删除数据');
        }
        // echo $id;
        if($this->_fre->where(array('id'=>array('eq',$id)))->delete()>0){
            $this->success('删除成功！',U('Shutup/index'));
        } else {
            $this->error('删除失败！');
        }
    }
}
