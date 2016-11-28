<?php
namespace Home\Controller;

use Think\Controller;

/**
 *  控制器名：FollownoteController
 *      控制器作用：关注帖子，取消关注
 *  @author wbx
 *  @date 2016-11-24
*/
class FollownoteController extends CommonController
{

   /**
    * 方法名 collect() 收藏
    * @param int note_id 帖子id
    */ 

    public function collect(){

        //判断有无登录
        if (empty($_SESSION['user']['name'])) {
            $this->error('您还没有登录，请先登录');
            exit;
        }
        //接受帖子的id
        $note_id = I('get.noteid/d');
        $data['user_id'] = $_SESSION['user']['id'];
        $data['note_id'] = $note_id;
        if (M('collect')->data($data)->add()>0) {
            $this->success('收藏成功！');
        }else{
            $this->error('收藏失败....');
        }
    }


    /**
    * 方法名 delcollect() 取消收藏
    * @param int noteid 帖子id
    */ 

    public function delcollect(){
       //接受你要取消收藏的帖子的id
        $note_id = I('get.noteid/d');
        if (empty($note_id)) {
            $this->error('操作失误！');
            exit;
        }

        $data['user_id']=$_SESSION['user']['id'];
        $data['note_id'] = $note_id;
        if (M('collect')->where($data)->delete()>0) {
            $this->success('取消收藏成功！');
        }else{
            $this->error('取消收藏失败........');
        }
    }

}