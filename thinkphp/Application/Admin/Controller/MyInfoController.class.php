<?php 

namespace Admin\Controller;

  /**
   * 后台管理员个人信息  修改个人信息 修改密码
   *  @author xiao
   * date 2016-11-14
  */ 

class MyInfoController extends AdminController
{ 
	private $_admin = null;//后台管理员表操作
	private $_like = null;//爱好表操作
    
   /**
    * 方法名：_initalize()
    *
    * @return[void]  初始化操作
    */
	public function _initialize(){
		parent::_initialize();
		$this->_admin = M('admin');
		$this->_like = M('like');

	}

	public function updatepwd(){
		$id =$_SESSION['admin_info']['id'];
		$data = $this->_admin->where(array('id'=>array('eq',$id)))->find();
		$list = $this->_like->select();
		$li = $this->_admin->field('like')->find($id);
        $arr['like']= explode(',',$li['like']); 
		// var_dump($list);
		$this->assign('list',$list);
		$this->assign('title','个人中心');
		$this->assign('stitle','修改个人资料');
		$this->assign('data',$data);
		$this->assign('arr',$arr);
		$this->display('MyInfo/updatepwd');
	}

	// 执行修改密码

	public function dopwd(){
	
		$id = $_POST['id'];
	
		$data['password'] = md5($_POST['newpwd']);	

		$res = $this->_admin->where(array('id'=>array('eq',$id)))->save($data);
		
		if ($res !==false) {
			$this->success('恭喜您，修改密码成功',U('Login/login'));
		}else{
			$this->error('修改密码失败.....');
		}
	}

	// 执行修改个人信息

	public function dodata(){
		 if (!empty($_POST)) {
          $array=$_POST['likename'];
          $str = implode(',', $array);
        }

         $data['like']=$str;
        $data['id'] = $_SESSION['admin_info']['id'];
        $data['sex'] = $_POST['sex'];
        $data['email'] = $_POST['email'];
        $data['introduction'] = $_POST['introduction'];
        // var_dump($data);die;
        if ($this->_admin->save($data) !==false) {
            $this->success('恭喜您，修改資料成功',U('Index/index'));
        }else{
            $this->error('修改資料失敗....');
        }
	}
}