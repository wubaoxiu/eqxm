<?php
namespace Admin\Controller;

class AdminUserController extends AdminController
{
    public function adminUserAdmin(){
        $this->display('AdminUser/adminUser');
    }
}