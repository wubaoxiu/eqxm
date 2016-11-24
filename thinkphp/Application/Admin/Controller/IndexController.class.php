<?php
namespace Admin\Controller;

class IndexController extends AdminController 
{
    public function index()
    {
        // $name = $_SESSION['admin_info']['name'];
        // dump($name);
        $id = $_SESSION['admin_info']['id'];
        $data = M('admin')->field('hpic,name')->find($id);

        $this->assign('title','首页');
        // $this->assign('name',$name);
        $this->assign('data',$data);
        $this->display('Index/index');
    }
}