<?php
namespace Home\Controller;

use Think\Controller;

/**
 *  控制器名：ApplyController
 *      控制器作用：增加楼层，修改楼层状态(显示不显示)
 *  @author wbx
 *  @date 2016-11-25
*/
class CreateBarController extends CommonController
{
    /**
     * 方法名：index()  获取创建贴吧页面
     * @return [void]
    */
    public function index()
    {
        $name = I('name');
        $arr = $this->getOption();
        // dump($arr);
        $this->assign('list',$arr);
        $this->assign('barname',$name);

        $this->display("CreateBar/index");
    }

    //
    public function doCreate()
    {
        if($_POST['yzm'] != session('sms')){
            $this->error("手机验证码错误！！！");
            exit;
        }

        $map = [];
        $map['type_id'] = $_POST['type_id'];
        $map['barname'] = $_POST['name'];
        $map['title'] = $_POST['title'];
        $map['desc'] = $_POST['desc'];
        $map['time'] = time();
        $map['username'] = $_SESSION['user']['name'];
        // dump($map);exit;

        $result = M('barinfo')->where(array('name'=>array('eq',$map['name'])))->find();

        if(!$result){
            $res = M('requestcreatebar')->data($map)->add();

            if($res>0){
                $this->success("请求创建已发送，我们会尽快响应您的请求，在此之前先去别的吧看看吧",U("Index/index"));
                exit;
            }else{
                $this->error("请求创建未发送成功！！！");
                exit;
            }
        }
        
    }

    /**
     * 方法名：getOption()  查询所有分类
     * @return [array] $arr  二维数组
    */
    private function getOption()
    {
        $data = M('type')->field('id,name,path')->select();
        $arr = array();
        foreach($data as $v){
            $count=substr_count($v['path'],',');
            $repeat=str_repeat('♔',$count-1);
            $v['newname'] = $repeat.$v['name'];
            $arr[] =$v;
        }
        return $arr;
    }
}