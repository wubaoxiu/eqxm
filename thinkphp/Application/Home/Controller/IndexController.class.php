<?php
namespace Home\Controller;

use Think\Controller;

/**
 *  控制器名：IndexController
 *      控制器作用：遍历首页信息
 *  @author wbx
 *  @date 2016-11-18
*/
class IndexController extends Controller {
    public function index(){
        //推荐贴吧信息
        dump($_SESSION['user']);
        $suggest = M('barinfo')->where(array('status'=>array('eq','1')))->order('attention desc')->limit(3)->select();

        $arr = array();
        foreach($suggest as $v){
            $v['count'] = M('note')->field(array("count(bar_id)"=>"count"))->where(array('bar_id'=>array('eq',$v['id'])))->select();

            $arr[] = $v;
        }
        // dump($arr);

        //全部贴吧推荐
        $bars = M('barinfo')->where(array('status'=>array('eq','1')))->order('attention desc')->limit(8)->select();

        $list = array();
        foreach($bars as $v){
            $v['count'] = M('note')->field(array("count(bar_id)"=>"count"))->where(array('bar_id'=>array('eq',$v['id'])))->select();

            $list[] = $v;
        }
        // dump($list);

        //热门帖子
        $note = M('note')->field("n.id,n.title,n.user_id,n.content,n.isfine,b.name,n.createtime")->table('csw_note n,csw_barinfo b')->where('n.istop=1 and n.is_show=1 and n.bar_id=b.id')->select();
        // dump($note);

        //友情链接
        $links = M('link')->where(array('is_show'=>array('eq','1')))->select();
        // dump($links);

        $this->assign('suggest',$arr);
        $this->assign('bars',$list);
        $this->assign('notes',$note);
        $this->assign('links',$links);
        $this->display();
    }
}