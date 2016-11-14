<?php 
namespace Admin\Model;
use Think\Model;

// 自动验证节点添加和修改时表单的提交数据
class NodeModel extends Model
{
    protected $_validate = array(
        array('node','require','节点名不能为空'),
        array('node','','节点名不能相同',0,'unique',3),
        array('cname','require','控制器不能为空'),
        array('aname','require','操作名不能为空'),
    );
}




