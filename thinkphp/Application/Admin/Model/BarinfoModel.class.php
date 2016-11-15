<?php
namespace Admin\Model;

use \Think\Model;

class BarinfoModel extends Model{
    protected $_validate = array(
        // array(验证字段1,验证规则,错误提示,[验证条件,附加规则,验证时间]),
        array('name','require','分类名不能为空！'),
    );

    protected $_auto = array(
        // array(完成字段1,完成规则,[完成条件,附加规则]),
        array('createtime','time',1,'function'),
        array('path','getPath',1,'callback'),
    );
}