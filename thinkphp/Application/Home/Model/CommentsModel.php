<?php
namespace \Home\CommentsModel;

use \Think\Model;

class CommentsModel extends Model()
{
    protected $_validate = array(
        array('note_id','require','帖子id不能为空'), //
        array('content','require','内容不能为空'), //
    );
    protected $_auto = array (
        array('createtime','time',1,'function'), // 在添加的时候写入当前时间戳        
    );
}