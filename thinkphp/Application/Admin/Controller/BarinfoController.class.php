<?php
namespace Admin\Controller;

class BarinfoController extends AdminController
{
    public function _initialize()
    {
        parent::_initialize();
    }

    public function index()
    {
        $this->display('Barinfo/index');
    } 
}