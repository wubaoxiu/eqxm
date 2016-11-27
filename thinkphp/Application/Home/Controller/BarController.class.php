<?php
namespace Home\Controller;

use Think\Controller;


/*
 * 控制器名：BarController
 *     控制器作用：加载贴子楼层页面，处理贴子评论、楼层评论
 * @author wbx
 * @date 2016-11-21
*/

class BarController extends CommonController
{
    // 定义数据库操作类
    private $_barinfo = null;
    private $_note = null;
    private $_user = null;
    private $_bars = null;
    private $_baradmin = null;

    // 初始化
    public function _initialize()
    {
        $this->_barinfo = M('barinfo');
        $this->_note = M('note');
        $this->_user = M('user');
        $this->_bars = M('bars');
        $this->_baradmin = M('baradmin');
    }

    // 主页
    public function index()
    {
        // 获取传过来的贴吧id
        $id = I('id');
        if(empty($id)){
            $this->error('您老迷路了吧，快去选择想进入的贴吧！');
        }
        $isfine = I('isfine');
        $istop = I('istop');
        $atten = $this->is_attentionBar($id);
        // dump($id,$isfine,$istop);die;
        
        //关注的吧
        if ($_SESSION['user']) {
            $attenbars = attentionBars();
            $signstatus = $this->getSign($id);
        }

        //贴吧管理员信息
        $baradmin_info = $this->getBarAdmin($id);
        // echo $id;
        // dump($baradmin_info);

        $data = $this->_barinfo->where(array('id' => array('eq', $id)))->find();
        // dump($data);
        if (empty($data)) {
            U('index/index');
            exit;
        }
        // 查询所有该贴吧中帖子的信息,查询帖子的要求
        if(empty($isfine)&&empty($istop)){
            $map['bar_id'] = $id;
            $status = 1;
        }elseif($isfine){
            $map['bar_id'] = $id;
            $map['isfine'] = $isfine;
            $status = 2;            
        }elseif($istop){
            $map['bar_id'] = $id;
            $map['istop'] = $istop;
            $status = 3;
        }
        // dump($map);die;
        $list = $this->_note->where($map)->order('id desc')->page($_GET['p'],5)->select();
        $pagenum = $this->_note->where($map)->count();
            // dump($pagenum);die;
        $pagenow = $_GET['p'];
        $page = new \Think\Page($pagenum,5);
        $show = $page->show();
        $pagenum = $this->_note->where($map)->count();
        // dump($pagenum);
        $pagenow = $_GET['p'];
        // echo $pagenow;
        // $page = new \Think\Page($pagenum,5);
        $show = $page->show();
        // 查询所有该贴吧中帖子的楼主信息
        foreach ($list as $k=>$v) {
            // dump($v);
            $list[$k]['louzhu'] = $this->_user->where(array('id' => array('eq', $v['user_id'])))->find();
        }

        // 检测用户是否为该贴吧的管理人员
        $baradmin = $this->_baradmin->field('status')->where(array('bar_id' => array('eq', $id), 'user_id' => array('eq', $_SESSION['user']['id'])))->find();
        // $attenbars = attentionBars();
        // dump($list);die;
        // dump($is_baradmin);die;
        if (empty($baradmin)) {
            $data['baradmin'] = 3;
        } else {
            $data['baradmin'] = $baradmin['status'];
            $_SESSION['admin']['bar_id'] = $id;
            $_SESSION['admin']['status'] = $baradmin['status'];
        }


        $bazu = $this->_barinfo->field('u.name')->table('csw_user u,csw_barinfo b')->where("u.id=b.user_id and b.id=$id")->select();
        // dump($bazu);

        //热门帖子
        $hotnote = $this->_note->where(array("bar_id"=>array('eq',$id)))->limit(5)->order('reply desc')->select();
        // dump($hotnote);

        $this->assign('status',$status);
        $this->assign('list',$list);
        $this->assign('data',$data);
        $this->assign('attenbars',$attenbars);
        $this->assign('atten',$atten);
        $this->assign('bar_id',$id);
        $this->assign('signstatus',$signstatus);
        $this->assign('title','CSW贴吧');
        $this->assign('show',$show);
        $this->assign('baradmin_info',$baradmin_info);
        $this->assign('bazu',$bazu);
        $this->assign('hotnote',$hotnote);

        // dump($attenbars);
        $this->display();
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
        $collect = $this->is_collect($noteid);

        //贴吧管理员信息
        $baradmin_info = $this->getBarAdmin($barid);

        if ($_SESSION['user']) {
            $attenbars = attentionBars();
            $signstatus = $this->getSign($barid);
        }
        // echo $signstatus;

        //贴子主要信息
        $count = M('note')->field(array('count(bar_id)' => "count"))->where("bar_id=$barid")->select();
        $data = M('note')->field(array("b.name bname", "n.bar_id", "n.id", "b.attention", "b.hpic bhpic", "u.hpic uhpic", "u.name uname", "n.content", "n.createtime", "n.title"))->table("csw_barinfo b,csw_note n,csw_user u")->where("b.id=$barid and n.user_id=u.id and n.id=$noteid")->select();
        // dump($data);
        // dump($count);
        //贴吧楼层

        $reply = M('floor')->field(array("f.content","f.note_id","u.name uname","u.hpic uhpic","u.id uid","f.createtime","f.floor"))->table("csw_user u,csw_floor f")->where("f.note_id=$noteid and f.user_id=u.id")->page($_GET['p'],5)->select();

        // dump($reply);
        $pagenum = M('floor')->where("bar_id=$barid and note_id=$noteid")->count();
        // dump($pagenum);
        $pagenow = $_GET['p'];
        // echo $pagenow;

        $page = new \Think\Page($pagenum,5);

        $show = $page->show();
        foreach ($reply as $v) {
            $v['content'] = htmlspecialchars_decode($v['content']);
            // dump($v);
            $replys[] = $v;
        }
        //楼层评论
        $comments = M('comments')->field("u.name uname,c.content,c.createtime,u.hpic,c.floor cfloor")->table("csw_user u,csw_comments c")->where("c.note_id=$noteid and c.user_id=u.id and c.status=1")->select();
        // dump($comments);
        // dump($replys);

         $bazu = $this->_barinfo->field('u.name')->table('csw_user u,csw_barinfo b')->where("u.id=b.user_id and b.id=$barid")->select();
        // dump($bazu);

         //热门话题
         $hotnote = $this->_note->where(array("bar_id"=>array('eq',$barid)))->limit(5)->order('reply desc')->select();


        $this->assign('count',$count);
        $this->assign('data',$data);
        $this->assign('comments',$comments);
        $this->assign("replys",$replys);
        $this->assign("atten",$atten);
        $this->assign("attenbars",$attenbars);
        $this->assign('show',$show);
        $this->assign('pagenow',$pagenow);
        $this->assign('bar_id',$barid);
        $this->assign('signstatus',$signstatus);
        $this->assign('title',"CSW贴吧");
        $this->assign('collect',$collect);
        $this->assign('baradmin_info',$baradmin_info);
        $this->assign('bazu',$bazu);
        $this->assign('hotnote',$hotnote);


        $this->display("Bar/note");
    }

    /**
     * 方法名：manage()  贴吧的管理界面主体
     * @author yjx 11/23
    */

    public function manage()
    {
        $id = I('get.id');
        $baradmin = I('get.baradmin');
        $data = $this->_barinfo->where(array('id' => array('eq', $id)))->find();
        // dump($data);
        if (empty($data)) {
            U('index/index');
            exit;
        }
        $data['baradmin'] = $baradmin;
        $this->assign('title', '帖吧管理');
        $this->assign('stitle', '贴吧信息');
        $this->assign('data', $data);
        $this->display();
    }

    /**
     * 方法名：gl_bar()  贴吧的信息管理界面
     * @author yjx 11/23
    */
    public function gl_bar()
    {
        $id = I('get.id');
        $baradmin = I('get.baradmin');
        $data = $this->_barinfo->where(array('id' => array('eq', $id)))->find();
        // dump($data);
        if (empty($data)) {
            U('index/index');
            exit;
        }
        $data['baradmin'] = $baradmin;
        $data['notes'] = $this->_note->where(array('bar_id' => array('eq', $id)))->count();
        // echo $this->_note->getLastSql();die;
        $this->assign('title', '帖吧管理');
        $this->assign('stitle', '贴吧信息管理');
        $this->assign('data', $data);
        $this->display();
    }


    /**
     * 方法名：gl_admin()  吧主管理贴吧页 对贴吧管理员的遍历
     * @author yjx 11/24
    */
    public function gl_admin()
    {
        $id = I('id');
        $admin = $this->_baradmin->field('user_id')->where(array('bar_id'=>array('eq',$id),'status'=>array('neq',1)))->select();
        foreach ($admin as $v) {
            $data[] = $this->_user->where(array('id'=>array('eq',$v['user_id'])))->order('id desc')->find();
        }
        // dump($data);die;
        $this->assign('bar_id',$id);
        $this->assign('title','帖吧管理');
        $this->assign('stitle','贴吧管理员');
        $this->assign('data',$data);
        $this->display();
    }
    /**
     * 方法名：gl_admin()  吧主管理贴吧页 对贴吧管理员的遍历
     * @author yjx 11/24
    */
    public function gl_fans()
    {
        $id = I('id');
        $fans = $this->_bars->where(array('bar_id'=>array('eq',$id)))->select();
        foreach ($fans as $k=>$v) {
            // 查询关注本贴吧的会员信息，过滤掉其中的管理员与吧主
            $fans[$k] = $this->_baradmin->field('status')->where(array('user_id'=>array('eq',$v['user_id'])))->find();
            $fans[$k]['bars'] = $this->_bars->field('integral,grade')->where(array('user_id'=>array('eq',$v['user_id'])))->find();
            $fans[$k]['user'] = $this->_user->field('id,name,sex,logintime')->where(array('id'=>array('eq',$v['user_id'])))->order('id desc')->find();
        }
        // dump($fans);die;
        $this->assign('bar_id',$id);
        $this->assign('title','帖吧管理');
        $this->assign('stitle','本吧会员');
        $this->assign('data',$fans);
        $this->display();
    }

    // 添加管理员
    public function add_admin()
    {
        $data['bar_id'] = I('bar_id');
        $data['user_id'] = I('user_id');
        $data['status'] = 2;
        if($this->_baradmin->data($data)->add()>0){
            $this->success('升级管理员成功！');
        }else{
            $this->error('升级管理员失败！');
        }
    }

    // 撤销管理员
    public function del_admin()
    {
        $data['bar_id'] = I('bar_id');
        $data['user_id'] = I('user_id');
        if($this->_baradmin->where($data)->delete()>0){
            $this->success('撤销管理员成功！');
        }else{
            $this->error('撤销管理员失败！');
        }
    }



    public function c_info()
    {
        $data['id'] = $_POST['id'];
        $data['desc'] = $_POST['desc'];
        if ($this->_barinfo->data($data)->save() > 0) {
            $this->success('修改成功！');
        } else {
            $this->error('修改失败！');
        }
        // echo $this->_barinfo->getLastSql();die;
    }

    //处理背景  
    public function updateAvator1()
    {
        $bgpic = $this->upload1();
        // dump($bgpic);
        $data['bgpic'] = $bgpic;
        $data['id'] = I('get.id/d');
        $this->_barinfo->save($data);
        return $this->ajaxReturn($bgpic);
    }

    public function upload1()
    {
        $upload = new \Think\Upload();// 实例化上传类
        $upload->thumbType = 1;// 缩略图生成方式 1 按设置大小截取 0 按原图等比例缩略
        $upload->maxSize = 3145728;// 设置附件上传大小
        $upload->exts = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath = './Uploads/bgimg/'; // 设置附件上传根目录
        $upload->savePath = ''; // 设置附件上传（子）目录
        // 上传文件
        $info = $upload->upload();
        if (!$info) {// 上传错误提示错误信息
            $this->error($upload->getError());
        } else {
            //获取上传后的路径和文件名
            // dump($info);
            $path = './Uploads/bgimg/' . $info['file']['savepath'];
            $name = $info['file']['savename'];

            $big = ltrim($path . $name);

            $image = new \Think\Image();
            $image->open($big);
            // 生成一个缩放后填充大小150*150的缩略图并保存为thumb.jpg
            $image->thumb(1100, 150)->save($path . 's_' . $name);
            unlink($big);
            $small = ltrim($path . 's_' . $name);
            //$arr = array('small'=>$small,'big'=>$big);
            return $small;

        }
    }

    //处理头像
    public function updateAvator2()
    {
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

    public function upload()
    {
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize = 3145728;// 设置附件上传大小
        $upload->exts = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath = './Uploads/'; // 设置附件上传根目录
        $upload->savePath = ''; // 设置附件上传（子）目录
        // 上传文件
        $info = $upload->upload();
        if (!$info) {// 上传错误提示错误信息
            $this->error($upload->getError());
        } else {
            //获取上传后的路径和文件名

            $path = $info['file']['savepath'];
            $name = $info['file']['savename'];

            $big = ltrim('./Uploads/' . $path . $name);

            $image = new \Think\Image();
            $image->open($big);
            // 生成一个缩放后填充大小150*150的缩略图并保存为thumb.jpg
            $image->thumb(150, 150)->save('./Uploads/' . $path . 's_' . $name);
            unlink($big);
            $small = ltrim($path . 's_' . $name);
            //$arr = array('small'=>$small,'big'=>$big);
            return $small;

        }
    }


    /**
     * 方法名：getSign()  获取签到信息
     * @return [boolean]  "0"为此吧还未签到 "1"为此吧已经签到
    */
    private function getSign($barid){
        $map = [];
        $map['bar_id'] = $barid;
        $map['user_id'] = $_SESSION['user']['id'];

        $data = M('bars')->where($map)->find();

        $now = time();
        $nowtime = date("ymd",$now);
        $signtime = date("ymd",$data['signtime']);

        if($nowtime !== $signtime){
            return "0";
        }else{
            return "1";
        }
    }

    /**
     * 方法名：doComment()  层内评论
     * @return ajax
     */
    public function doComment()
    {
        if (empty($_SESSION['user'])) {
            $this->error("你还未登录，请先登录！");
            exit;
        }
        $comment = M('Comments');

        $data = array();
        $data['note_id'] = I("post.note_id");
        $data['user_id'] = $_SESSION['user']['id'];
        $data['createtime'] = time();
        $data['floor'] = I("post.floor");
        $data['content'] = I("post.content");
        // dump($data);exit;

        if (!$data) {
            $this->ajaxReturn(0);
            exit;
        } else {
            $cid = $comment->data($data)->add();

            $this->ajaxReturn($cid);
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
        if (empty($res)) {
            return 0;
        } else {
            return 1;
        }
    }

       /**
        *   方法名 collect() 收藏帖子
        *  
        *   @author xiao
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
    * 方法名 delcollect() 取消关注
    * @param int noteid 好友id
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
        // var_dump($data);die;
        if (M('collect')->where($data)->delete()>0) {
            $this->success('取消收藏成功！');
        }else{
            $this->error('取消收藏失败........');
        }
    }

      
    /**
    *  定义一个私有方法
    *  方法名 is_collect()  判断是否关收藏了此贴吧
    *  @param int id  好友id
    *   @return string 1 表示收藏 2 表示已收藏
    */ 
    private function is_collect($noteid){
        //定义一个空数组
        $arr = [];
        $arr['user_id'] =$_SESSION['user']['id'];
        $arr['note_id'] = $noteid;
        $res = M('collect')->where($arr)->select();
        if (empty($res)) {
            return 1;
        }else{
            return 2;
        }

    }
    

    /**
     * 方法名：getBarAdmin()  获取贴吧管理员信息
     * @return []
    */
    private function getBarAdmin($barid)
    {
        // $barid=4;
        $baradmin = M('baradmin');
        $data = $baradmin->field("u.hpic,u.name,b.grade,a.status")->table("csw_user u,csw_baradmin a,csw_bars b")->where("a.bar_id=$barid and a.user_id=u.id and a.user_id=b.user_id and b.bar_id=$barid")->select();
        // dump($data);
        return $data;
    }

    public function baidu()
    {
        $this->display();
    }
   

}



