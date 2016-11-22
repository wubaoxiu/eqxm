<?php 


namespace Home\Controller;
use Think\Controller;

    /**
    * 我的主页
    * @author xiao
    * date 2016-11-19
    */ 
    class MyInfoController extends Controller{ 

        public function index()
        {


          $id = I('get.id/d');
          $list = M('user')->find($id);
          $love_bar = M('barinfo')->field('name')->where(array('id'=>$id))->select();
          // var_dump($love_bar);
          $hot = M('barinfo')->select();
          // var_dump($hot);

          $follow = $this->is_follow($id);
          // $data = $this->_barinfo->find($id);
          // var_dump($list);
          $this->assign('list',$list);
          $this->assign('follow',$follow);
          $this->assign('love',$love_bar);
          $this->assign('hot',$hot);
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
               $fuser_id = I('get.id/d');

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
