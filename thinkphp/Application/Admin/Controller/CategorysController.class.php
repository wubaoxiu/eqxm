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
        dump($_SESSION);
        $this->display('Categorys/index');
    }
}