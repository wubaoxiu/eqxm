<?php 
namespace Home\Controller;

use Think\Controller;



/*
 * 控制器名：BarController
 *     控制器作用：加载贴子楼层页面，处理贴子评论、楼层评论
 * @author wbx
 * @date 2016-11-21
*/

class BarController extends Controller
{
    // 定义数据库操作类
    private $_barinfo = null;
    private $_note = null;
    private $_user = null;
    private $_baradmin = null;

    // 初始化
    public function _initialize()
    {
        $this->_barinfo = M('barinfo');
        $this->_note = M('note');
        $this->_user = M('user');
        $this->_baradmin = M('baradmin');
    }

    // 主页
    public function index()
    {
        // 获取传过来的贴吧id

        // $id = I('bar_id');
        // $id = 1;

        // dump($_SESSION['user']);
        $id = I('id');
        // $id = 1;

        $atten = $this->is_attentionBar($id);

        //关注的吧
        if($_SESSION['user']){           
            $attenbars = attentionBars();
        }
        // dump($list);
        // dump($attenbars);

        $data = $this->_barinfo->where(array('id'=>array('eq',$id)))->find();
        // dump($data);
        if(empty($data)){
            U('index/index');exit;
        }
        // 查询所有该贴吧中帖子的楼主信息
        $list = $this->_note->where(array('bar_id'=>array('eq',$id)))->order('id desc')->select();
        foreach ($list as $k=>$v) {
            // dump($v);
            $list[$k]['louzhu'] = $this->_user->where(array('id'=>array('eq',$v['user_id'])))->find();
        }

        // 检测用户是否为该贴吧的管理人员
        $baradmin = $this->_baradmin->field('status')->where(array('bar_id'=>array('eq',$id),'user_id'=>array('eq',$_SESSION['user']['id'])))->find();
        // $attenbars = attentionBars();
        // dump($list);die;
        // dump($is_baradmin);die;
        if(empty($baradmin)){
            $data['baradmin'] = 2;
        }else{
            $data['baradmin'] = $baradmin['status'];
        }

        

        $this->assign('list',$list);
        $this->assign('data',$data);
        $this->assign('attenbars',$attenbars);
        $this->assign('atten',$atten);
        $this->assign('bar_id',$id);

        // dump($attenbars);
        $this->display();
    }

    /**
     * 方法名：manage()  贴吧的管理界面主体
    */
    public function manage()
    {
        $id = I('get.id');
        $baradmin = I('get.baradmin');
        $data = $this->_barinfo->where(array('id'=>array('eq',$id)))->find();
        // dump($data);
        if(empty($data)){
            U('index/index');exit;
        }
        $data['baradmin'] = $baradmin;
        $this->assign('title','帖吧管理');
        $this->assign('stitle','贴吧信息');
        $this->assign('data',$data);
        $this->display();
    }

    /**
     * 方法名：gl_bar()  贴吧的信息管理界面
    */
    public function gl_bar()
    {
        $id = I('get.id');
        $baradmin = I('get.baradmin');
        $data = $this->_barinfo->where(array('id'=>array('eq',$id)))->find();
        // dump($data);
        if(empty($data)){
            U('index/index');exit;
        }
        $data['baradmin'] = $baradmin;
        $data['notes'] = $this->_note->where(array('bar_id'=>array('eq',$id)))->count();
        // echo $this->_note->getLastSql();die;
        $this->assign('title','帖吧管理');
        $this->assign('stitle','贴吧信息管理');
        $this->assign('data',$data);
        $this->display();
    }
    public function c_info()
    {
        $data['id'] = $_POST['id'];
        $data['desc'] = $_POST['desc'];
        if($this->_barinfo->data($data)->save()>0){
            $this->success('修改成功！');
        }else{
            $this->error('修改失败！');
        }
        // echo $this->_barinfo->getLastSql();die;
    }
    //处理背景
    public function updateAvator1(){
        $bgpic = $this->upload1();
        // dump($bgpic);
        $data['bgpic'] = $bgpic;
        $data['id'] = I('get.id/d');
        $this->_barinfo->save($data);
        return $this->ajaxReturn($bgpic);
    }

    public function upload1(){
        $upload = new \Think\Upload();// 实例化上传类
        $upload->thumbType = 1;// 缩略图生成方式 1 按设置大小截取 0 按原图等比例缩略
        $upload->maxSize = 3145728 ;// 设置附件上传大小
        $upload->exts = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath = './Uploads/bgimg/'; // 设置附件上传根目录
        $upload->savePath = ''; // 设置附件上传（子）目录
        // 上传文件
        $info = $upload->upload();
        if(!$info) {// 上传错误提示错误信息
            $this->error($upload->getError());
        }else{
            //获取上传后的路径和文件名
            // dump($info);
            $path = './Uploads/bgimg/'.$info['file']['savepath'];
            $name = $info['file']['savename'];

            $big = ltrim($path.$name);
            
            $image = new \Think\Image();
            $image->open($big);
            // 生成一个缩放后填充大小150*150的缩略图并保存为thumb.jpg
            $image->thumb(1100, 150)->save($path.'s_'.$name);
            unlink($big);
            $small = ltrim($path.'s_'.$name);
            //$arr = array('small'=>$small,'big'=>$big);
            return $small;

        }
    }

     //处理头像
    public function updateAvator2(){
        $hpic = $this->upload();
        // dump($hpic);
        $data['hpic'] = $hpic;
        $data['id'] = I('get.id/d');
        $this->_barinfo->save($data);
        return $this->ajaxReturn($hpic);
    } 

    /**
    *  图片上传
    */ 

   public function upload(){
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize = 3145728 ;// 设置附件上传大小
        $upload->exts = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath = './Uploads/'; // 设置附件上传根目录
        $upload->savePath = ''; // 设置附件上传（子）目录
        // 上传文件
        $info = $upload->upload();
        if(!$info) {// 上传错误提示错误信息
            $this->error($upload->getError());
        }else{
            //获取上传后的路径和文件名
            
            $path = $info['file']['savepath'];
            $name = $info['file']['savename'];

            $big = ltrim('./Uploads/'.$path.$name);
            
            $image = new \Think\Image();
            $image->open($big);
            // 生成一个缩放后填充大小150*150的缩略图并保存为thumb.jpg
            $image->thumb(150, 150)->save('./Uploads/'.$path.'s_'.$name);
            unlink($big);
            $small = ltrim($path.'s_'.$name);
            //$arr = array('small'=>$small,'big'=>$big);
            return $small;

        }
    }

    /**
     * 方法名：note()  显示贴子内容页面
     * @return [void]
    */
    public function note()
    {
        $barid = I('bar_id');
        // $barid = 3;
        $noteid = I('note_id');
        // $noteid = 1;

        $atten = $this->is_attentionBar($barid);

        if($_SESSION['user']){           
            $attenbars = attentionBars();
        }
        // echo $atten;

        //贴子主要信息
        $count = M('note')->field(array('count(bar_id)'=>"count"))->where("bar_id=$barid")->select();
        $data = M('note')->field(array("b.name bname","n.bar_id","n.id","b.attention","b.hpic bhpic","u.hpic uhpic","u.name uname","n.content","n.createtime","n.title"))->table("csw_barinfo b,csw_note n,csw_user u")->where("b.id=$barid and n.user_id=u.id and n.id=$noteid")->select();
        // dump($data);
        // dump($count);
        //贴吧楼层
        $reply = M('floor')->field(array("f.content","f.note_id","u.name uname","u.hpic uhpic","u.id uid","f.createtime","f.floor"))->table("csw_user u,csw_floor f")->where("f.note_id=$noteid and f.user_id=u.id")->page($_GET['p'],2)->select();
        // dump($reply);
        $pagenum = M('floor')->where("bar_id=$barid and note_id=$noteid")->count();
        // dump($pagenum);
        $pagenow = $_GET['p'];
        // echo $pagenow;
        $page = new \Think\Page($pagenum,2);
        $show = $page->show();
        foreach($reply as $v){
            $v['content'] = htmlspecialchars_decode($v['content']);
            // dump($v);
            $replys[] = $v;
        }
        //楼层评论
        $comments = M('comments')->field("u.name uname,c.content,c.createtime,u.hpic,c.floor cfloor")->table("csw_user u,csw_comments c")->where("c.note_id=$noteid and c.user_id=u.id and c.status=1")->select();
        // dump($comments);
        // dump($replys);

        $this->assign('count',$count);
        $this->assign('data',$data);
        $this->assign('comments',$comments);
        $this->assign("replys",$replys);
        $this->assign("atten",$atten);
        $this->assign("attenbars",$attenbars);
        $this->assign('show',$show);
        $this->assign('pagenow',$pagenow);
        $this->assign('bar_id',$barid);

        $this->display("Bar/note");
    }

    /**
     * 方法名：doComment()  层内评论
     * @return ajax
    */
    public function doComment()
    {
        if(empty($_SESSION['user'])){
            $this->error("你还未登录，请先登录！");
            exit;
        }
        $comment = M('Comments');

        $data = array();
        $data['note_id'] = I("post.note_id");
        $data['user_id'] = I("post.user_id");
        $data['createtime'] = time();
        $data['floor'] = I("post.floor");
        $data['content'] = I("post.content");
        // dump($data);exit;

        if(!$data){
            $this->ajaxReturn(0);
            exit;
        }else{
            $cid = $comment->data($data)->add();

            $this->ajaxReturn($cid);
        }
    }
    
    /**
     * 方法名：attentionBars()  关注贴吧
     * @return [void]
    */
    public function attentionBars()
    {
        if(empty($_SESSION['user'])){
            $this->error("你还未登录，请先登录！");
            exit;
        }

        $bar_id = I("barid");
        if(empty($bar_id)){
            $this->error("操作失误！！！");
            exit;
        }

        $data['bar_id'] = $bar_id;
        $data['user_id'] = $_SESSION['user']['id'];
        // dump($data);
        $bars = M('bars');

        if($bars->data($data)->add()>0){
            $this->success("关注成功！！！");
            exit;
        }else{
            $this->error("关注失败！！！");
            exit;
        }
    }

    //
    public function cancelBars()
    {
        $bar_id = I("barid");
        if(empty($bar_id)){
            $this->error("操作失误！！！");
            exit;
        }

        $data['bar_id'] = $bar_id;
        $data['user_id'] = $_SESSION['user']['id'];
        // dump($data);
        $bars = M('bars');

        if($bars->where($data)->delete()>0){
            $this->success("取消关注成功！！！");
            exit;
        }else{
            $this->error("取消关注失败！！！");
            exit;
        }
    }

    /**
     * 方法名：is_attentionBar()  判断是否关注此吧
     * @param [int] $barid  吧ID
     * @return [string]  0为未关注 1为已关注
    */
    private function is_attentionBar($barid)
    {
        $map = [];
        $map['user_id'] = $_SESSION['user']['id'];
        $map['bar_id'] = $barid;
        $res = M('bars')->where($map)->select();
        // dump($res);
        if(empty($res)){
            return 0;
        }else{
            return 1;
        }
    }

   
}



