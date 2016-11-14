<?php
namespace Admin\Model;

use \Think\Model;

class TypeModel extends Model{
    protected $_validate = array(
        // array(验证字段1,验证规则,错误提示,[验证条件,附加规则,验证时间]),
        array('name','require','分类名不能为空！'),
    );

    protected $_auto = array(
        // array(完成字段1,完成规则,[完成条件,附加规则]),
        array('createtime','time',1,'function'),
        array('path','getPath',1,'callback'),
    );

    //获取路径的方法
    protected function getPath()
    {
        $list = $this->field('id,path')->find(I('pid'));
        if($list){
            $path = $list['path'].$list['id'].',';
        }else{
            $path = "0,";
        }
        return $path;
    }

    /*
        * 查询用于后台的分类
        * @param $pid    父id
        * @param $field  查询字段
    */
    public function getAdminType($pid=0, $field=true)
    {            
        //获取所有分类信息
        $list = $this->field($field)->where(array('pid'=>$pid))->select();
        //返回
        return $list;
    }

    /*
        * 查询用于前台的分类
        * @param $id     分类ID
        * @param $field  查询的字段
    */
    public function getHomeType($id=0, $field=true)
    {       
        $list = $this->field($field)->select();  //获取所有分类信息
        //处理分类信息
        $list = $this->getTreeList($list, $id , 'child');

        //如果存在id
        if(is_numeric($id) && $id>0){
            $arr = $this->field($field)->find($id);
            $arr['child'] = $list;
            return $arr;
        }

        return $list;
    }

    protected function getTreeList($treelist, $pid=0 , $name='child')
    {
        $arr = array();
        foreach($treelist as $v){
            if($v['pid'] == $pid){
                $v[$name] = $this->getTreeList($treelist, $v['id']);
                $arr[] = $v;
            }
        }
        return $arr;
    }
}