<?php
namespace Admin\Controller;

class BarUserController extends AdminController
{
    private $_bar = null;//贴吧表操作
    public function _initialize(){
        parent::_initialize();
        $this->_bar = D('Bar');

    }
     public function index(){
        $data = $this->_Bar->select();
        $this->display('BarUser/index');
    }
}