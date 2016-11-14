<?php 
namespace Admin\Model;
use Think\Model;
/**
    *   模板名称 RoleModel
    *   模板作用 自动验证角色表添加和修改时表单提交的数据
    *   @author     yjx
    *   @date 2016/11/10
    */

// 验证表单提交的数据
class RoleModel extends Model
{
    // $_validata自动验证
    protected $_validate = array(
        array('role','require','角色名不能为空'),//新增和修改数据是判断name字段是否为空
        array('role','','角色名已经存在',0,'unique',3),// 新增和修改数据时判断name字段是否唯一
        array('explain','require','角色说明不能为空'),// 新增和修改数据时判断字段explain是否为空
    );
}




