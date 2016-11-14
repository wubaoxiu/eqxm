<?php
namespace Admin\Controller;

class TypeController extends AdminController
{
    public function _initialize()
    {
        parent::_initialize();
    }

    //分类列表
    public function index()
    {
        $pid = I("get.id");
        $d = D('Type');
        $data = $d->getAdminType($pid);
        // dump($data);
        if(empty($data)){
            $this->error("没有子分类！！！");
            // $this->ajaxReturn(false);
            exit;
        }
        $this->assign('title',"贴吧分类");
        $this->assign('typelist',$data);
        $this->display('Type/index');
    }

    //获取添加页面
    public function add()
    {
        $this->display('Type/add');
    }

    //执行添加
    public function doAdd()
    {
        $d = D('Type');

        if(!$d->create()){
            $this->error($d->getError());
            exit;
        }

        if($d->add()>0){
            $this->success("恭喜你，添加成功！",U('Type/index'));
        }else{
            $this->error("添加失败");
        }
    }

    //获取修改页面
    public function edit()
    {
        // $id = I(get.id);
        $data = M('Type')->where(array('id'=>array('eq',I('id'))))->find();
        // dump($data);
        $this->assign('list',$data);
        $this->display('Type/edit');
    }

    //执行修改
    public function save()
    {
        $d = D('Type');

        if(!$d->create()){
            $this->error($d->getError());
            exit;
        }

        if($d->save()>=0){
            $this->success("恭喜你，修改成功！",U('Type/index'));
        }else{
            $this->error("修改失败");
        }
    }

    //执行删除
    public function del()
    {
        $d = D('Type');

        $map = array();
        $map['id'] = array('eq',I('id'));
        $map['path'] = array('like','%'.I('id').'%');
        $map['_logic'] = 'or';
        // dump($map);

        if($d->where($map)->delete()>0){
            $this->success("恭喜你，删除成功！",U('Type/index'));
        }else{
            $this->error("删除失败！");
        }
    }

    //分类树列表
    public function treeList()
    {
        $d = D('Type');
        $data = $d->getHomeType();
        // dump($data);
        $this->assign('list',$data);
        $this->display('Type/treeList');
    }
}