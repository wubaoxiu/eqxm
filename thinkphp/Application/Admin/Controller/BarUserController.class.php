<?php
namespace Admin\Controller;

class BarUserController extends AdminController
{
    public function barUserAdmin(){
        $this->display('BarUser/barUser');
    }
}