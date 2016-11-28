<?php 

namespace Home\Controller;
use Think\Controller;

    /**
    * 我的主页
    * @author xiao
    * date 2016-11-19
    */ 
  class MyInfoController extends CommonController{ 

      private $_user = null; //用户表操作
      private $_bars = null;//吧信息表操作
      private $_fans = null;//关注用户表操作
      private $_barinfo = null;//吧信息表操作

    public function _initialize(){
        $this->_user = M('user');
        $this->_bars = M('bars');
        $this->_fans = M('fans');
        $this->_barinfo = M('barinfo');
      }

    public function index()
        {
          //判断有无登录
          if (empty($_SESSION['user']['name'])) {
              $this->error('您还没有登录，请先登录');
              exit;
          }
            // 该用户的id
          $uid = I('get.uid/d');
          // 查询该用户的所有信息
          $list = $this->_user->find($uid);

          //查询该用户所关注的吧（爱逛的吧）
          $love_bar = $this->_barinfo->field('bi.name,bi.id')->where("b.user_id=$uid and b.bar_id=bi.id")->table('csw_barinfo bi,csw_bars b')->select();
    
          //查询热门的吧
          $hot = $this->_barinfo->select();

           // 查询该用户关注的好友
          $atten = $this->_fans->field('fuser_id')->where(array('user_id'=>array('eq',$uid)))->select();
          foreach ($atten as $v) {
            // var_dump($v);
            $fname[] = $this->_user->field('hpic,id')->where(array('id'=>$v['fuser_id']))->select();
        
          }
          // 统计该用户所关注的好友人数
           $count = $this->_fans->field('fuser_id')->where(array('user_id'=>array('eq',$uid)))->count(); 


           // 查询关注我的人
          $atten1 = $this->_fans->field('user_id')->where(array('fuser_id'=>array('eq',$uid)))->select();
          foreach ($atten1 as $value) {
            $fname1[] = $this->_user->field('hpic,id')->where(array('id'=>$value['user_id']))->select();
        
          }
          // 统计关注我的好友人数
           $count1 = $this->_fans->field('uuser_id')->where(array('fuser_id'=>array('eq',$uid)))->count();
           // 调用了受保护的内部方法 （关注与否）
          $follow = $this->is_follow($uid);
          $this->assign('list',$list);
          $this->assign('follow',$follow);
          $this->assign('collect',$collect);
          $this->assign('love',$love_bar);
          $this->assign('hot',$hot);
          $this->assign('fname',$fname);
          $this->assign('count',$count);  
          $this->assign('fname1',$fname1);
          $this->assign('count1',$count1);
          $this->display();

        }
    public function personal()
      {

        //判断有无登录
        if (empty($_SESSION['user']['name'])) {
          $this->error('您还没有登录，请先登录');
          exit;
        }
        // 该用户的id
        $uid = I('get.uid/d');
        // 查询该用户的所有信息
        $list = $this->_user->find($uid);

        //查询该用户所关注的吧（爱逛的吧）
        $love_bar = $this->_barinfo->field('bi.name,bi.id')->where("b.user_id=$uid and b.bar_id=bi.id")->table('csw_barinfo bi,csw_bars b')->select();
        //查询热门的吧
        $hot = $this->_barinfo->select();

       // 查询该用户关注的好友
        $atten = $this->_fans->field('fuser_id')->where(array('user_id'=>array('eq',$uid)))->select();
        foreach ($atten as $v) {
          $fname[] = $this->_user->field('hpic,id')->where(array('id'=>$v['fuser_id']))->select();
        // 统计该用户所关注的好友人数
           $count=$this->_user->where(array('id'=>$v['fuser_id']))->count();
        }
         // 调用了受保护的内部方法 （关注与否）
        $follow = $this->is_follow($uid);
        $this->assign('list',$list);
        $this->assign('follow',$follow);
        $this->assign('collect',$collect);
        $this->assign('love',$love_bar);
        $this->assign('hot',$hot);
        $this->assign('fname',$fname);
        $this->assign('count',$count);
        $this->display('MyInfo/personal');
      }

        /**
        * 方法名 follow() 關注好友
        *  @return [void]
        */ 

    public function follow(){
      //判断有无登录
      if (empty($_SESSION['user']['name'])) {
        $this->error('您还没有登录，请先登录');
        exit;
      }
      //获取好友要关注的好友的id
       $fuser_id = I('get.uid/d');

       if (empty($fuser_id)) {
         $this->error('操作失误！');
         exit;
       }
      $data['user_id'] = $_SESSION['user']['id'];
      $data['fuser_id'] = $fuser_id;
      if($this->_fans->data($data)->add()>0){
        $this->success('关注成功！',U('Profile/index'));
      }else{
        $this->error('关注失败.......');
      }
     }

        /**
        * 方法名 cancel() 取消关注
        * @param int uid 好友id
        */ 
    public function cancel(){
        //接受你要取消关注的人的id
        $fuser_id = I('get.uid');
        if (empty($fuser_id)) {
           $this->error('操作失误！');
           exit;
        }

        $data['user_id']=$_SESSION['user']['id'];
        $data['fuser_id'] = $fuser_id;
        if ($this->_fans->where($data)->delete()>0) {
           $this->success('取消关注成功！');
        }else{
          $this->error('取消关注失败');
        }
     }


      /**
      *  定义一个私有方法
      *  方法名 is_follow()  判断是否关注了好友
      *  @param int id  好友id
      *   @return string 1 表示关注 2 表示未关注
      */ 

    private function is_follow($id){
     // 定义一个空数组
      // var_dump($id);
      $arr = [];
      $arr['fuser_id']=$id;
      $arr['user_id']=$_SESSION['user']['id'];
       $res = $this->_fans->where($arr)->select();
      if (empty($res)) {
        return 1;
      }else{
        return 2;
      }
    }


     

    }
