<?php 

namespace Home\Controller;
use Think\Controller;

   /**
   *  修改个人资料  修改个人头像 查看所关注的吧信息 
   *   关注的好友 以及收藏的帖子
   *   @author xiao
   *  date 2016-11-22
   */ 

class SetController extends Controller{

    private $_user = null;//用户表操作
    private $_like = null;//爱好表操作
    private $_barinfo = null;//吧信息表操作
    private $_bars = null;//用户关注吧表操作
    private $_fans = null;//粉丝表操作

    public function _initialize(){
      $this->_user=M('user');
      $this->_like=M('like');
      $this->_barinfo=M('barinfo');
      $this->_bars=M('bars');
      $this->_fans=M('fans');
    }

      public function detail(){
        //获取id
        $id = $_SESSION['user']['id'];
        //查找该用户的信息
        $list = $this->_user->find($id);
        // var_dump($id);
        //分配数据
        $this->assign('list',$list);
        $this->display();
    }

   //处理头像 
    public function dophoto(){

        //获取用户id
        $id=$_SESSION['user']['id'];
        //查询该用户下的所有信息
        $list = $this->_user->find($id);
        $data = $this->_like->select();
         $li = $this->_user->field('like')->find($id);
        $arr['like']= explode(',',$li['like']); 
        //分配数据
        $this->assign('list',$list);
        $this->assign('data',$data);
        $this->assign('arr',$arr);
         $this->display('Set/dophoto');
    }

     //修改头像

    public function action(){
      $hpic = $this->upload();
      $data['hpic'] = $hpic;
        $data['id'] = I('get.id/d');
       $this->_user->save($data);
       return $this->ajaxReturn($hpic);
    }

    
       // 头像上传
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


    //修改資料
    public function dodata(){ 
        if (!empty($_POST)) {
          $array=$_POST['likename'];
          $str = implode(',', $array);
        }

         $data['like']=$str;
        $data['id'] = $_POST['id'];
        $data['sex'] = $_POST['sex'];
        $data['brith'] = $_POST['brith'];
        $data['introduction'] = $_POST['introduction'];
        // var_dump($data);die;
        if ($this->_user->save($data) !==false) {
            $this->success('恭喜您，修改資料成功',U('Set/detail'));
        }else{
            $this->error('修改資料失敗....');
        }
    }


      /**
        * 方法名 mybar() 我的贴吧 个人中心 我的关注的吧的查询 
        * 
        *  @param 用户id
        *
        */ 

    public function mybar(){

        $id=$_SESSION['user']['id']; 
        $list = $this->_bars->field('bi.name,b.id,b.integral,b.grade')->where("b.bar_id=bi.id and b.user_id='$id'")->table('csw_bars b,csw_barinfo bi')->select();
        $this->assign('list',$list);
        $this->display('Set/mybar');
    }

    /**
    * 对我关注的贴吧(取消关注)
    * 方法名 delfollowbar()
    * @return 影响行数
    */ 

    public function delfollowbar(){
        //接受吧id
        $id = I('post.id/d');
        var_dump($id);
        $res = $this->_bars->where(array('id'=>array('eq',$id)))->delete();
        if ($res) {
        return $this->ajaxReturn($res);
        }
    } 

    /**
    *  方法名 friend() 关注的好友 
    *   @param 用户id  整型 int
    *   
    */ 

    public function friend(){
      $id = $_SESSION['user']['id'];
      // 查询该用户所关注的好友的id
       $list = $this->_fans->field('fuser_id')->where("user_id=$id")->select();
       foreach($list as $k=>$v){
        //根据好友的id 查找该好友的用户名 
          $arr[$k]['name'] =$this->_user->field('name')->where(array('id'=>$v['fuser_id']))->find(); 
       }
      $this->assign('arr',$arr);
      $this->display('Set/friend');
    }
      /**
       * 对我关注的好友(取消关注)
       * 方法名 delfriend()
       * 
       */ 

     public function delfriend(){
        //好友id
        $id = I('post.id');
         $res = $this->_score->where(array('user_id'=>array('eq',$id)))->delete();
        if ($res) {
          $this->_fans->where(array('fuser_id'=>array('eq',$id)))->delete();
         return $this->ajaxReturn($res);
        }
    } 
   

     /*
     *  方法名 collect() 对帖子的收藏
     *  
     */

     public function collect(){
      // /定义一个空数组
      $arr = [];
      $list = M('note')->field('n.title,n.createtime,n.user_id')->where('c.note_id=n.id')->table('csw_note n,csw_collect c')->select();
      // var_dump($list);
      foreach($list as $v){
        $author = $this->_user->field('name')->where(array('id'=>$v['user_id']))->select();
      }
    
        $arr['title']=$v['title'];
        $arr['time']=$v['createtime'];
        $arr['name']=$author[0]['name'];
        // var_dump($arr);
        $this->assign('arr',$arr);
        $this->display('Set/collect');
     }


}