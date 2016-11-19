<?php 
    

namespace Home\Controller;
use \Think\Controller;

class WeatherController extends Controller{


     public function index(){
        $cityName = "上海";
        $city = iconv('UTF-8','GBK',$cityName);
        $url = 'http://php.weather.sina.com.cn/xml.php?city='.$city.'&password=DJOYnieT8234jlsK&day=0';

        // //curl初始化
        // $curl = curl_init();
        // // var_dump($curl);
        // //URL 设置
        // curl_setopt($curl,CURLOPT_URL,$curl);

        // //将curl_exec()获取的信息以文件流的形式返回，而不是直接输出
        // curl_setopt($curl,  CURLOPT_RETURNTRANSFER, true);

        // //curl执行
        // $data = curl_exec($curl);
        $api = new \Common\Util\ResultApi();
        // var_dump($api);
        $data = $api->getApiData($url, 'json');
        var_dump($data);
        $this->assign('data',$data);
        $this->display('Weather/index');



     }
}