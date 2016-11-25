<?php
namespace Admin\Controller;

class IndexController extends AdminController 
{
    protected function _model()
    {
    return new \Think\Model();
    }
    public function index()
    {
      
        $id = $_SESSION['admin_info']['id'];
        $data = M('admin')->field('hpic,name')->find($id);
        $count = M('admin')->count();

         $sysinfo = array(
            'os' => $_SERVER["SERVER_SOFTWARE"], //获取服务器标识的字串
            'version' => PHP_VERSION, //获取PHP服务器版本
            'time' => date("Y-m-d H:i:s", time()), //获取服务器时间
            'pc' => $_SERVER['SERVER_NAME'], //当前主机名
            'osname' => php_uname(), //获取系统类型及版本号
            'language' => $_SERVER['HTTP_ACCEPT_LANGUAGE'], //获取服务器语言
            'port' => $_SERVER['SERVER_PORT'], //获取服务器Web端口
            'max_upload' => ini_get("file_uploads") ? ini_get("upload_max_filesize") : "Disabled", //最大上传
            'max_ex_time' => ini_get("max_execution_time")."秒", //脚本最大执行时间
            'mysql_version' => $this->_mysql_version(),
            'mysql_size' => $this->_mysql_db_size(),
        );
        // var_dump($count);

        $this->assign('title','CSW');
        $this->assign('stitle','网址基本信息');
        // $this->assign('name',$name);
        $this->assign('sysinfo',$sysinfo);
        $this->assign('data',$data);
        $this->assign('count',$count);
        $this->display('Index/index');
    }

        private function _mysql_version()
    {
        $Model = self::_model();
        $version = $Model->query("select version() as ver");
        return $version[0]['ver'];
    }
    private function _mysql_db_size()
    {        
        $Model = self::_model();
        $sql = "SHOW TABLE STATUS FROM ".C('DB_NAME');
        $tblPrefix = C('DB_PREFIX');
        if($tblPrefix != null) {
            $sql .= " LIKE '{$tblPrefix}%'";
        }
        $row = $Model->query($sql);
        $size = 0;
        foreach($row as $value) {
            $size += $value["Data_length"] + $value["Index_length"];
        }
        return round(($size/1048576),2).'M';
    }
}