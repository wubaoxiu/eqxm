<?php 

namespace Admin\Controller;

    /**
    *友情链接
    *@author xiao
    *date 2016-11-15
    */ 

class Linkcontroller extends AdminController
{
   private $_link = null;//友情链接表操作s


  
    /**
    * 方法名 _initialize()  初始化操作
    * @return[void]
    */
   public function _initialize()
   {
    parent::_initialize();
    $this->_link = D('Link');
   }

   /*
   *友情链接列表
   */

   public function index(){
    $list = $this->_link->select();
    $this->assign('title','友情链接列表');
    $this->assign('list',$list);
    $this->display('Link/index');
   }


   /**
   *获取添加友情链接页面
   * @return void 
   */ 

   public function add(){
    $this->assign('title','友情链接列表');
    $this->assign('stitle','添加友情链接');
    $this->display('Link/add');
   }



   /**
   * 执行添加
   */ 
   public function doAdd(){
    //获取id
    $id = I('post.id/d');

    if (!$this->_link->create()) {
        //如果没有创建没有完成 则验证失败
        //输出错误信息 并跳转
        $this->error($this->_link->getError());
        exit;
    }else{
        //创建成功 执行添加
        if ($this->_link->add()>0) {
            $this->success('恭喜您，添加成功！',U('Link/index'));
        }else{
            $this->error('添加失败.....');
        }
    }

   }

   /**
   * 删除链接
   * @return void
   **/ 

   public function del(){
      //判断有无id
    if (empty($_GET['id'])) {
        $this->redirect('Admin/Link/index');
        exit;
    }
     //过滤
    $id = I('get.id/d');
    if ($this->_link->delete($id)>0) {
        $this->success('恭喜您，删除成功',U('Link/index'));
    }else{
        $this->error('删除失败.....');
    }
   }

   /**
    * 获取修改链接页面
    */ 

   public function edit(){

    //获取id
      $id = I('get.id/d');
      $data = $this->_link->find($id);
      $this->assign('stitle','编辑友情链接');
      $this->assign('data',$data);
      $this->display('Link/edit');
     }

     /**
     * 执行修改操作
     */ 
     public function update(){
      //判断POST有无值
      if (empty($_POST)) {
        $this->redirect('Admin/Link/index');
        exit;
      }
        $this->_link->create();
      if ($this->_link->save()>0) {
          $this->success('恭喜您，修改成功！',U('Link/index'));
      }else{
        $this->error('修改失败.....');
      }
     }


    /**
    *  方法名称 doshow()
    *
    * @return [vold]   描述显示或不显示
    *
    */ 

    public function doshow(){
        $id = I('get.id/d');
        $show = $this->_link->where(array('id'=>array('eq',$id)))->getField('is_show');
      dump($is_show);
      if ($show) {
          $data['is_show']=0;
          $this->_link->where(array('id'=>array('eq',$id)))->save($data);
          $this->ajaxReturn(2);
      }else{
         $data['is_show']=1;
         $this->_link->where(array('id'=>array('eq',$id)))->save($data);
         $this->ajaxReturn(1);
      }

    }





}