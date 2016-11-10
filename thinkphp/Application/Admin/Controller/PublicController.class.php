<?php
namespace Admin\Controller;

class PublicController extends AdminController
{
    public function newl()
    {
        $this->display('Public/newl');
    }
}