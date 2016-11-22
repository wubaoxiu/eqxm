<?php
namespace Home\Model;
use \Think\Model;

class FloorModel extends Model
{
    protected $_validate = array(
        array('note_id','require','帖子id不能为空'), //
        array('content','require','内容不能为空'), // 
        array('bar_id','require','吧ID不能为空'),
    );
    protected $_auto = array (
        // array('id',null), // 新增的时候把status字段设置为null
        // array('user_id','getId',1,'callback'), //
        array('createtime','time',1,'function'), // 对found_time字段在添加的时候写入当前时间戳
        // array('sort','setSort',1,'callback'), // 
        // array('bar_as_id','setBarAsId',1, 'callback'),
        // array('reception','setReception',1,'callback'),
        array('floor','getFloor',1,'callback'),
        array('user_id','getUserId',1,'callback'),
    );

    protected function getFloor()
    {
        $noteid = $_POST['note_id'];

        $max = M('floor')->field("max(floor) max")->where("note_id=$noteid")->select();
        if($max[0]['max'] == 0){
            $max[0]['max'] += 1;
        }
        return $max[0]['max']+1;
    }

    protected function getUserId()
    {
        $user_id = $_SESSION['user']['id'];
        return $user_id;
    }
}