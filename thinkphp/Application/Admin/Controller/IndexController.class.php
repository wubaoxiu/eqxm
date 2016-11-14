<?php
namespace Admin\Controller;

class IndexController extends AdminController 
{
    public function index()
    {
        $name = $_SESSION['admin_info']['name'];
        // dump($name);
        $this->assign('title','首页');
        $this->assign('name',$name);
        $this->display('Index/index');
    }
}