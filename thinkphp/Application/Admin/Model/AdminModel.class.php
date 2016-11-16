<?php 
    namespace Admin\Model;
    use Think\Model;
    class AdminModel extends Model
    {
        //自动完成
        protected $_auto = array(
          array('password','md5',3,'function')
          );
        //自动验证
        protected $_validate = array(
          // array(验证字段1,验证规则,错误提示,[验证条件,附加规则,验证时间]),
          array('name','require','用户名不能为空',0,'regex',1),
          array('name','','帐号名称已经存在！',0,'unique',1), 
          array('password','require','密码不能为空',0,'regex',1),
          array('repassword','password','确认密码不正确',0,'confirm',1),//验证确认密码与密码是否相符合
          array('email','/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/','邮箱格式不正确',0,'regex',1)

          );
    }