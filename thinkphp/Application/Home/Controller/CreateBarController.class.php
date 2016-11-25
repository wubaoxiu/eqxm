<?php
namespace Home\Controller;

use Think\Controller;

/**
 *  控制器名：ApplyController
 *      控制器作用：增加楼层，修改楼层状态(显示不显示)
 *  @author wbx
 *  @date 2016-11-25
*/
class CreateBarController extends Controller
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
        dump($_POST);

        $map = [];
        $map['type_id'] = $_POST['type_id'];
        $map['name'] = $_POST['name'];
        $map['title'] = $_POST['title'];
        $map['desc'] = $_POST['desc'];
        $map['createtime'] = time();
        // dump($map);exit;

        $result = M('barinfo')->where(array('name'=>array('eq',$map['name'])))->find();

        if(!$result){
            $res = M('barinfo')->data($map)->add();

            if($res>0){
                $this->success("创建成功,去创建的吧看看吧",U("Bar/index",array('id'=>$res)));
                exit;
            }else{
                $this->error("创建失败！！！");
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