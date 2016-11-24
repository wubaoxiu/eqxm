<?php 


namespace Home\Controller;
use Think\Controller;

    /**
    * 我的主页
    * @author xiao
    * date 2016-11-19
    */ 
    class MyInfoController extends Controller{ 

      private $_user = null; //用户表操作
      private $_barinfo = null;//吧信息表操作
      private $_fans = null;//关注用户表操作

      public function _initialize(){
        $this->_user = M('user');
        $this->_barinfo = M('barinfo');
        $this->_fans = M('fans');
      }

        public function index()
        {

            // 该用户的id
          $uid = I('get.uid/d');
              var_dump($uid);
          // 查询该用户的所有信息
          $list = $this->_user->find($uid);

          //查询该用户所关注的吧（爱逛的吧）
          $love_bar = $this->_barinfo->field('name')->where(array('user_id'=>$uid))->select();
    
          //查询热门的吧
          $hot = $this->_barinfo->select();
          // var_dump($hot);
           

           // 查询该用户关注的好友
          $atten = $this->_fans->field('f.fuser_id')->where("f.user_id=u.id and f.user_id='$uid'")->table('csw_user u,csw_fans f')->select();
          foreach ($atten as $v) {
            $fname = $this->_user->field('u.hpic')->where('f.fuser_id = u.id')->table('csw_user u,csw_fans f')->select();
          }
          // 统计该用户所关注的好友人数
          $count=$this->_user->field('u.hpic')->where('f.fuser_id = u.id')->table('csw_user u,csw_fans f')->count();
          var_dump($count);

           // 调用了受保护的内部方法 （关注与否）
          $follow = $this->is_follow($uid);
          // var_dump($list);
          $this->assign('list',$list);
          $this->assign('follow',$follow);
          $this->assign('love',$love_bar);
          $this->assign('hot',$hot);
          $this->assign('fname',$fname);
          $this->assign('count',$count);
           $this->display();

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
              // var_dump($data);die;
              if(M('fans')->data($data)->add()>0){
                $this->success('关注成功！');
              }else{
                $this->error('关注失败.......');
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
           $res = M('fans')->where($arr)->select();
          if (empty($res)) {
            return 1;
          }else{
            return 2;
          }
        }
    }
