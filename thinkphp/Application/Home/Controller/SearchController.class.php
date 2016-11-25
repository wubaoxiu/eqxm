<?php
namespace Home\Controller;

use Think\Controller;

/**
 *  控制器名：SearchController
 *      控制器作用：接口
 *  @author wbx
 *  @date 2016-11-24
*/
class SearchController extends Controller
{
    public function search()
    {
        $bars = I('post.bars');
        // echo $bars;
        $data = M('barinfo')->where(array('name'=>array('eq',$bars)))->find();

        // dump($data);
        if(!$data){
            $this->ajaxReturn(0);
        }else{
            $this->ajaxReturn($data['id']);
        }
    }
}