<?php
namespace Admin\Controller;

class BarinfoController extends AdminController
{
    private $_barinfo = null;
    private $_user = null;
    private $_type = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->_barinfo = D('Barinfo');
        $this->_user = D('User');
        $this->_type = D('Type');
    }

    public function index()
    {
        $data = $this->_barinfo->select();
        $arr = array();

        foreach($data as $v){
            $uname = array();  //定义一个空数组，用来存放创建人名
            $uname = $this->_user->field('name')->where(array('id'=>array('eq',$v['user_id'])))->find();
            $v['username'] = $uname;

            $tname = array();  //定义一个空数组，用来存放分类名
            $tname = $this->_type->field('name')->where(array('id'=>array('eq',$v['type_id'])))->find();
            $v['typename'] = $tname;

            $arr[] = $v;
        }
        // dump($arr);
        $this->assign('list',$arr);
        $this->display('Barinfo/index');
    }

    public function add()
    {
        $data = $this->_type->field('id,name,path')->select();
        $arr = array();
        foreach($data as $v){
            $count=substr_count($v['path'],',');
            $repeat=str_repeat('♔',$count-1);
            $v['newname'] = $repeat.$v['name'];
            $arr[] =$v;
        }
        // dump($arr);
        $this->assign('list',$arr);
        $this->display("Barinfo/add");
    }

    public function doAdd()
    {
        // dump($_POST);
        if($_FILES['url']['error'] == 0){           
            $upload = new \Think\Upload();// 实例化上传类
            $upload->exts = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
            $upload->rootPath = './Uploads/'; // 设置附件上传根目录
            // 上传文件
            $info = $upload->upload();
            if(!$info) {
                // 上传错误提示错误信息
                $this->error($upload->getError());
            }else{
                // 上传成功
                // $this->success('上传成功！');
                // $model = M('Barinfo');
                $arr = array();
                foreach($info as $file){
                    // echo $file['savepath'].$file['savename'];
                    $arr[] = $file['savepath'].$file['savename'];
                }
                // $data['user_id'] = $_SESSION['admin_info']['id'];
                $data['name'] = I("post.name");
                $data['type_id'] = I("post.type_id");
                $data['createtime'] = time();
                $data['title'] = I("post.title");
                $data['desc'] = I("post.desc");
                $data['hpic'] = $arr[0];
                $data['bgpic'] = $arr[1];
                // $data['url']=$info['url']['savepath'].$info['url']['savename'];
                // dump($data);
                if($data['type_id']==0){
                    $this->error("请选择具体贴吧类别！！！");
                    exit;
                }

                if(empty($data['title'])){
                    $this->error("贴吧宣言不能为空！！！");
                    exit;
                }
                if($this->_barinfo->field('id')->where(array('name'=>array('eq',$data['name'])))){
                    $this->error("该贴吧名已存在！！！");
                    exit;
                }
                if($this->_barinfo->data($data)->add()){
                    $this->success("恭喜您，创建成功！！！",U('Barinfo/index'));
                    exit;
                }
                unlink($data['hpic']);
                unlink($data['bgpic']);
                $this->error('添加失败');
            }
            unlink($data['hpic']);
            unlink($data['bgpic']);
            $this->error('添加失败');
            exit;
        }else{
            $this->error("头像，背景图没上传！！！");
            exit;
        }
    }

    /*
        * 改变状态  （可用ajax，有bug未解决）
    */
    public function checkStatus()
    {
        $id = I('get.id');
        $status= $this->_barinfo->where(array('id'=>array('eq',$id)))->getField('status');
        // dump($status);exit;
        if($status){
            $data['status']=0;
            $this->_barinfo->where(array('id'=>array('eq',$id)))->save($data);
            $this->redirect("Barinfo/index");
        }else{
            $data['status']=1;
            $this->_barinfo->where(array('id'=>array('eq',$id)))->save($data);
            $this->redirect("Barinfo/index");
        }
    }

    //获取修改页面
    public function edit($id)
    {
        $id = I('id');
        if(empty($id)){
            $this->error("操作错误！！！");
        }
        $list = $this->_barinfo->where(array('id'=>array('eq',$id)))->find();
        // dump($data);
        $data = $this->_type->field('id,name,path')->select();
        $arr = array();
        foreach($data as $v){
            $count=substr_count($v['path'],',');
            $repeat=str_repeat('♔',$count-1);
            $v['newname'] = $repeat.$v['name'];
            $arr[] =$v;
        }
        // dump($list);

        //分配数据
        $this->assign('title',"修改贴吧信息");
        $this->assign('list',$arr);
        $this->assign('data',$list);

        $this->display("Barinfo/edit");
    }

    //执行修改
    public function save()
    {
        if(!$this->_barinfo->create()){
            $this->error("操作错误！！！");
            exit;
        }
        // dump($this->_barinfo->create());exit;

        if($this->_barinfo->save()>=0){
            $this->success("修改成功！！！",U("Barinfo/index"));
            exit;
        }else{
            $this->error("修改失败！！！");
            exit;
        }
    }

    //查看详情
    public function detail()
    {
        $this->display("Barinfo/detail");
    }
}