<?php
namespace Admin\Model;

use \Think\Model;

// class BarinfoModel extends Model{
//     protected $_validate = array(
//         // array(验证字段1,验证规则,错误提示,[验证条件,附加规则,验证时间]),
//         array('name','require','吧名不能为空！'),
//         array('title','0,60','宣言不多于20字',0,'length'),
//         array('name','','贴吧名称已经存在！',0,'unique'), // 在新增、修改的时候验证name字段是否唯一
//     );

//     protected $_auto = array(
//         // array(完成字段1,完成规则,[完成条件,附加规则]),
//         array('createtime','time',1,'function'),
//         array('user_id','getUserId',1,'callback'),
//     );

//     protected function getUserId()
//     {
//         $id = $_SESSION['admin_info']['id'];
//         return $id;
//     }
// }