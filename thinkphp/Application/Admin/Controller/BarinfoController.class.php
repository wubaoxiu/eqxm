<?php
namespace Admin\Controller;

/**
* 控制器名：BarinfoController
*     控制器作用：贴吧管理
* @author wbx
* @date  2016-11-17
*/
class BarinfoController extends AdminController
{
    private $_barinfo = null;
    private $_user = null;
    private $_type = null;

    /**
    * 方法名：_initalize()
    *
    * @return[void]  初始化操作
    */
    public function _initialize()
    {
        parent::_initialize();
        $this->_barinfo = D('Barinfo');
        $this->_user = M('User');
        $this->_type = M('Type');
    }

    /**
    * 方法名：index() 获取贴吧基本信息表
    *
    * @return[void]
    */
    public function index()
    {
        $arr = $this->_barinfo->getBarinformation();
        // dump($arr);
        $this->assign('list',$arr);
        $this->display('Barinfo/index');
    }

    /**
    * 方法名：add() 获取添加页面
    *
    * @return[void]
    */
    public function add()
    {
        $arr = $this->_barinfo->getOption();
        // dump($arr);
        $this->assign('list',$arr);
        $this->display("Barinfo/add");
    }

    /**
    * 方法名：doadd() 执行添加
    *
    * @return[void]
    */
    public function doadd()
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

    /**
    * 方法名：checkStatus() 改变状态  （可用ajax，有bug未解决）
    *
    * @return[void] 
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

    /**
    * 方法名：edit()
    *
    * @return[void] 获取修改页面
    */
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

    /**
    * 方法名：save() 执行修改
    *
    * @return[void]
    */
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

    /**
    *方法名：detail()
    *
    * @return[void]  返回贴吧详情页面
    */
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
        //查询吧管理员
        $list[] = M('baradmin')->field('u.name,u.id')->table('csw_baradmin b,csw_user u')->where("b.bar_id=$id and b.user_id=u.id and b.status=2")->select();
        // dump($data);
        dump($list);
        $arr = array_pop($list);
        dump($arr);
        // echo $arr[0]['name'];
        $this->assign('title',"贴吧管理");
        $this->assign('stitle','贴吧详情');
        $this->assign('data',$data); //分配贴吧信息
        $this->assign('list',$arr);  //分配管理员信息
        $this->display("Barinfo/detail");
    }

    /**
    * 方法名：upload()
    *
    *@return[void]  执行头像上传和修改
    */
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

    /**
    * 方法名称：Request()
    *
    * @return['void']  返回吧管理请求列表
    */
    public function Request()
    {
        $r = M('request');
        $data = $r->field('u.name uname,u.email uemail,b.name bname,r.id,r.status,r.user_id userid')->table('csw_barinfo b,csw_user u,csw_request r')->where('r.user_id=u.id and r.bar_id=b.id')->select();
        // dump($data);
        $this->assign('title','申请吧管理');
        $this->assign('list',$data);
        $this->display("Barinfo/request");
    }

    /**
    * 方法名：agree()
    *
    * @return['void'] 同意请求
    */
    public function agree()
    {
        $id = I('id');
        if(empty($id)){
            $this->error("操作失误！！！");
        }
        $r = M('request');

        $data = $r->field('r.user_id,r.bar_id,r.status,b.user_id uid')->table('csw_request r,csw_barinfo b')->where("r.id=$id and r.bar_id=b.id")->select();
        // dump($data);
        $list = array();
        $list['user_id'] = $data[0]['user_id'];
        $list['bar_id'] = $data[0]['bar_id'];
        $list['status'] = $data[0]['status'];

        // dump($list);exit;

        //判断是否申请吧主
        if($list['status'] == 1){
            //判断该吧是否已经存在吧主，不存在继续申请，存在则提示
            if($data[0]['uid']>0){
                M('baradmin')->where(array('id'=>$res))->delete(); //操作失败，将已插入的数据删除
                $this->error("此吧已有吧主，若要换吧主，请先撤销原吧主！");
                exit;
            } 
            $map = array();           
            $map['user_id'] = $list['user_id'];
            $map['id'] = $list['bar_id'];
            // dump($map);
            //将吧主插入到贴吧信息表,并将数据添加到贴吧管理表        
            if($this->_barinfo->data($map)->save()>0 && M('baradmin')->data($list)->add()>0){
                $r->where(array('id'=>$id))->delete();
                $this->success("已同意",U('Barinfo/request'));
                exit;
            }else{
                $this->error("操作失败！！！");
                exit;
            }
        }else if(M('baradmin')->data($list)->add()>0 && $r->where(array('id'=>$id))->delete()>0){
            $this->success("已同意",U('Barinfo/request'));
            exit;
        }
    }

    /**
    * 方法名：revoke()  撤销吧主
    *
    * @return[void]
    */
    public function revoke()
    {
        $id = I('id');
        if(empty($id)){
            $this->error("操作失误！！！");
            exit;
        }
        $data = array();
        $data['user_id'] = "";
        $data['id'] = $id;
        if($this->_barinfo->data($data)->save()>0 && M('baradmin')->where("bar_id=$id and status=1")->delete()>0){
            $this->success("撤销职位成功！！！");
            exit;
        }else{
            $this->error("撤销失败！！！");
            exit;
        }
    }

    /**
    * 方法名：annul()  撤销吧管理
    *
    * @return[void]
    */
    public function annul()
    {
        $id = I('id');
        if(empty($id)){
            $this->error("操作失误！！！");
            exit;
        }
        $map = array();
        $map['bar_id'] = $id;
        $map['status'] = '2';
        $map['user_id'] = I('user_id');
        // dump($map);exit;
        if(M('baradmin')->where($map)->delete()>0){
            $this->success("撤销吧管理员成功！");
            exit;
        }else{
            $this->error("撤销职位失败！");
            exit;
        }
    }
}