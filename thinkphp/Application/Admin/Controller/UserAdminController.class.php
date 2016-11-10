<?php
namespace Admin\Controller;

class UserAdminController extends AdminController 
{
    public function index()
    {
        $this->display('UserAdmin/index');
    }
}