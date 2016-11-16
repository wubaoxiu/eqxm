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
        $this->_user = M('User');
        $this->_type = M('Type');
    }

    public function index()
    {
        $arr = $this->_barinfo->getBarinformation();
        // dump($arr);
        $this->assign('list',$arr);
        $this->display('Barinfo/index');
    }

    //获取添加页面
    public function add()
    {
        $arr = $this->_barinfo->getOption();
        // dump($arr);
        $this->assign('list',$arr);
        $this->display("Barinfo/add");
    }

    //执行添加
    public function doAdd()
    {
        if(!$this->_barinfo->create()){
            $this->error($this->_barinfo->getError());
            exit;
        }

        if($this->_barinfo->add() > 0){
            $this->success("添加成功！！！",U("Barinfo/index"));
            exit;
        }else{
            $this->error("添加失败！！！");
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

        $arr = $this->_barinfo->getOption();
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
        $id = I('id');
        if(empty($id)){
            $this->error("操作失误！！！");
            exit;
        }
        $data = $this->_barinfo->where(array('id'=>array('eq',$id)))->find();
        $data['typename'] = $this->_type->field('name')->where(array('id'=>array('eq',$data['type_id'])))->find();
        $data['username'] = $this->_user->field('name')->where(array('id'=>array('eq',$data['user_id'])))->find();
        // dump($data);
        $this->assign('title',"贴吧管理");
        $this->assign('stitle','贴吧详情');
        $this->assign('data',$data);
        $this->display("Barinfo/detail");
    }

    //执行头像上传和修改
    public function upload()
    {
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
                $data['id'] = I("post.id");
                $data['hpic'] = $info['hpic']['savepath'].$info['hpic']['savename'];

                $image = new \Think\Image();
                $image->open("./Uploads/{$data['hpic']}");
                // 按照原图的比例生成一个最大为150*150的缩略图并保存为thumb.jpg
                $image->thumb(150, 150)->save("./Uploads/{$info['hpic']['savepath']}/s_"."{$info['hpic']['savename']}");

                if($this->_barinfo->data($data)->save()>0){
                    $this->success("头像上传成功！！！",U('Barinfo/index'));
                }else{                    
                    unlink($info['hpic']['savepath'].'/'.$info['hpic']['savename']);
                    $this->error('添加失败');
                }
            }
        }
    }

    public function Request()
    {
        $r = M('request');
        $data = $r->field('u.name uname,u.email uemail,b.name bname,r.id,r.status,r.user_id userid')->table('csw_barinfo b,csw_user u,csw_request r')->where('r.user_id=u.id and r.bar_id=b.id')->select();
        // dump($data);
        $this->assign('title','申请吧管理');
        $this->assign('list',$data);
        $this->display("Barinfo/request");
    }

    public function agree()
    {
        $id = I('id');
        if(empty($id)){
            $this->error("操作失误！！！");
        }
        $r = M('request');
        $data = $r->field('user_id,bar_id,status')->where(array('id'=>array('eq',$id)))->find();
        dump($data);
        if(M('baradmin')->data($data)->add()>0){
            if($data['status'] == 1){ 
                $map = array();           
                $map['user_id'] = $data['user_id'];
                $map['id'] = $data['bar_id'];
                dump($map);            
                if($this->_barinfo->data($map)->save()>0){
                    $r->where(array('id'=>$id))->delete();
                    $this->success("已同意",U('Barinfo/request'));
                }else{
                    $this->error("操作失误！！！");
                }
            }
        }else{
            $this->error("操作失误！！！");
        }
    }
}