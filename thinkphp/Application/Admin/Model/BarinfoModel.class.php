<?php
namespace Admin\Model;

use \Think\Model;

class BarinfoModel extends Model{
    protected $_validate = array(
        // array(验证字段1,验证规则,错误提示,[验证条件,附加规则,验证时间]),
        array('name','require','吧名不能为空！'),
        array('title','0,60','宣言不多于20字',0,'length'),
        array('name','','贴吧名称已经存在！',0,'unique',1), // 在新增、修改的时候验证name字段是否唯一
        array('title','require',"宣言不能为空！"),
        array('type_id','0','请选择贴吧分类！',0,'notequal',1),
    );

    protected $_auto = array(
        // array(完成字段1,完成规则,[完成条件,附加规则]),
        array('createtime','time',1,'function'),
    );

    public function getBarinformation()
    {
        $data = $this->select();
        $arr = array();

        foreach($data as $v){
            $uname = array();  //定义一个空数组，用来存放创建人名
            $uname = M('user')->field('name')->where(array('id'=>array('eq',$v['user_id'])))->find();

            if(empty($uname)){
                $map = array();
                $map['user_id'] = '';
                $map['id'] = $v['id'];
                $res = M('barinfo')->data($map)->save(); //把已删除账号的用户的吧主撤销
                // echo $res.'<br/>';
            }
            $v['username'] = $uname;

            $tname = array();  //定义一个空数组，用来存放分类名
            $tname = M('type')->field('name')->where(array('id'=>array('eq',$v['type_id'])))->find();
            $v['typename'] = $tname;

            $arr[] = $v;
        }
        return $arr;
    }

    public function getOption()
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