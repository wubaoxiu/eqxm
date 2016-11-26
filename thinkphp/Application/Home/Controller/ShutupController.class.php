<?php 
namespace Home\Controller;
use Think\Controller;

/**
    *@author yjx 
    *@date 11/26
    *前台把管理禁言用户模块
*/
class ShutupController extends ConmonController
{
    private $_shutup = null;

    public function _initialize()
    {
        $this->_shutup = M('shutup');
    }
    public function add()
    {
        // dump($_POST);
        $this->_shutup->where(array('bar_id'=>$_POST['bar_id'],'user_id'=>$_POST['user_id']))->delete();
        $data['bar_id'] = $_POST['bar_id'];
        $data['user_id'] = $_POST['user_id'];
        if($_POST['stime']==1){
            $data['relieve'] = time()+60;
        }elseif($_POST['stime']==2){
            $data['relieve'] = time()+120;
        }else{
            $data['relieve'] = time()+180;            
        }
        if($this->_shutup->data($data)->add()>0){
            $this->success('禁言成功！');
        }else{
            $this->error('禁言失败！');
        }
    }
}




