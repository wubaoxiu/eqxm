<?php 


namespace Home\Controller;
use Think\Controller;

   /**
   *  修改个人资料
   *   @author xiao
   *  date 2016-11-22
   */ 

class AvaterController extends Controller{

   
   //处理头像 
    public function dophoto(){

        //获取用户id
        $id=$_SESSION['user']['id'];
        //查询该用户下的所有信息
        $list = M('user')->find($id);

        $data = M('like')->select();
         $li = M('user')->field('like')->find($id);
        $arr['like']= explode(',',$li['like']); 
        //分配数据
        $this->assign('list',$list);
        $this->assign('data',$data);
        $this->assign('arr',$arr);
         $this->display('Avater/dophoto');
    }


    //修改資料
    public function dodata(){ 
        if (!empty($_POST)) {
          $array=$_POST['likename'];
          $str = implode(',', $array);
        }

         $data['like']=$str;
        $data['id'] = $_POST['id'];
        $data['sex'] = $_POST['sex'];
        $data['brith'] = $_POST['brith'];
        $data['introduction'] = $_POST['introduction'];
        // var_dump($data);die;
        if (M('user')->save($data) !==false) {
            $this->success('恭喜您，修改資料成功',U('Set/index'));
        }else{
            $this->error('修改資料失敗....');
        }


    }

}