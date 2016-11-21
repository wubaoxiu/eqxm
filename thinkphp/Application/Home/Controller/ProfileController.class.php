<?php 

namespace Home\Controller;
use Think\Controller;

    /**
    * 个人中心
    * @author xiao
    * date 2016-11-20
    *****/ 

class ProfileController extends Controller{

    public function index(){
        $id = $_SESSION['user']['id'];
        // var_dump($id);
          $list = M('user')->find($id);
          var_dump($list);
          $this->assign('list',$list);
           $this->display();

    }

    //修改头像

    public function action(){
         $hpic = $this->upload();
         console.log($hpic);
        $data['hpic'] = $hpic;
        $data['id'] = I('get.id/d');
        M('user')->save($data);
       return $this->ajaxReturn($hpic);

    }


        /**
        *  头像上传
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
            $image->thumb(150, 150,\Think\Image::IMAGE_THUMB_FILLED)->save('./'.$path.'s_'.$name);
            unlink($big);
            $small = ltrim($path.'s_'.$name);
            //$arr = array('small'=>$small,'big'=>$big);
            return $small;

        }
    }

    public function dophoto(){
        $id = I('get.id');
        $list = M('user')->find($id);
        $this->assign('list',$list);
    
    }


 }
