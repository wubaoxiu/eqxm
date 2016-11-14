<?php
namespace Admin\Controller;

class CategorysController extends AdminController
{
    public function _initialize()
    {
        parent::_initialize();
    }

    public function index()
    {
        $this->display('Categorys/index');
    }
}