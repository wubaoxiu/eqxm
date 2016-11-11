<?php
namespace Admin\Controller;

class UserController extends AdminController
{
    public function index(){
        $this->display('User/index');
    }
}