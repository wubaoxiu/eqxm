<?php 

namespace Admin\Controller;

  /**
   * 轮播图管理
   *  @author xiao
   * date 2016-11-25
  */ 

class PictureController extends AdminController
{ 
	private $_picture = null;//轮播图表操作

	    /**
    * 方法名：_initalize()
    *
    * @return[void]  初始化操作
    */
	public function _initialize(){
		parent::_initialize();
		$this->_picture = M('picture');

	}

	public function index(){
		$list = $this->_picture->select();
		// var_dump($list);
		$this->assign('title','轮播图管理');
		$this->assign('stitle','轮播图显示列表');
		$this->assign('list',$list);
		$this->display();

	}

	//获得修改图片的页面
	public function edit(){
		$id = I('get.id/d');
		$data = $this->_picture->find($id);
		
		$this->assign('title','轮播图显示列表');
		$this->assign('stitle','修改图片');
		$this->assign('data',$data);
		$this->display('Picture/edit');
	}
	//修改图片
	public function dopic(){

		$pic = $this->upload();
		$arr['id']=$_POST['id'];
		$arr['picname']=$pic;
		// var_dump($arr);die;
		$res = $this->_picture->save($arr);
		if ($res) {
			$this->success('恭喜您,修改成功',U('Picture/index'));
		}else{
			$this->error('修改失败......');
		}

	}

	/**
    *  图片上传
    */ 

   public function upload(){
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize = 3145728 ;// 设置附件上传大小
        $upload->exts = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath = 'Public/linkimg/'; // 设置附件上传根目录
        $upload->savePath = ''; // 设置附件上传（子）目录
        // 上传文件
        $info = $upload->upload();
        if(!$info) {// 上传错误提示错误信息
            $this->error($upload->getError());
        }else{
            //获取上传后的路径和文件名
            
            $path = 'Public/linkimg/'.$info['file']['savepath'];
            $name = $info['file']['savename'];

            $big = ltrim('./'.$path.$name);
            
            $image = new \Think\Image();
            $image->open($big);
            // 生成一个缩放后填充大小150*150的缩略图并保存为thumb.jpg
            $image->thumb(400,180,\Think\Image::IMAGE_THUMB_FIXED)->save('./'.$path.'s_'.$name);
            unlink($big);
            $small = ltrim($path.'s_'.$name);
            //$arr = array('small'=>$small,'big'=>$big);
            return $small;

        }
    }

    /**
    *  方法名称 doshow()
    *
    * @return [vold]   描述显示或不显示
    *
    */ 

    public function doshow(){
        $id = I('get.id/d');
        $show = $this->_picture->where(array('id'=>array('eq',$id)))->getField('is_show');
        // dump($show);
        if ($show) {
            $data['is_show']=0;
            $this->_picture->where(array('id'=>array('eq',$id)))->save($data);
            $this->ajaxReturn(2);
        }else{
           $data['is_show']=1;
           $this->_picture->where(array('id'=>array('eq',$id)))->save($data);
           $this->ajaxReturn(1);
        }
    }

    // 删除图片
    public function del(){

    	$id = I('get.id/d');
      
      //判断有无id
     if (empty($_GET['id'])) {
        $this->redirect('Admin/Link/index');
        exit;
      }
    	$res = $this->_picture->delete($id);
    	if ($res) {
    		$this->success('删除成功',U('Picture/index'));
    	}else{
    		$this->error('删除失败.....');
    	}
    }



}