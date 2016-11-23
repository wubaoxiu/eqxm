<?php

/**
 * 方法名：attentionBars()
 *   作用：查找所有我关注的吧
 * @param [string]  $limit  查找分页
 * @return [array]  $atten  返回一个数组
 *
 * @author wbx
 * @date 2016-11-21
*/
function attentionBars($limit=null)
{
    $uid = $_SESSION['user']['id'];
    // echo $uid;
    $atten = M('bars')->field('b.name,ba.signin,b.id,ba.integral,ba.grade,ba.id barsid')->table("csw_bars ba,csw_barinfo b")->where("ba.user_id=$uid and ba.bar_id=b.id")->limit($limit)->select();
    return $atten;
}

