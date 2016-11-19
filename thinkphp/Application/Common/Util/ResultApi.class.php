<?php
namespace Common\Util;

/**
 * 处理API返回的数据
 * @lastModified 2012-09-12
 * @author fuming
*/
class ResultApi{

	/**
     * 根据接口返回数据
     * @param string $url 接口地址
     * @param string $type 返回数据类型 默认 json,可选值 有"xml" "serialize"
     * @param array $header 请求头
     * @return object
	*/
	public function getApiData($url, $type='json', $header=array()){
		//返回字符串
		$dataStr = $this->getApiStr($url, $header);

		//定义变量
		$dataObj = null;

		//处理数据
		if ($type == 'xml') {

		} else {
			$dataObj = json_decode($dataStr);
		}

		//返回结果
		return $dataObj;
	}


	/**
      *根据 接口 返回字符串
      * @param $url
      * @param $header
	*/
	public function getApiStr($url, $header){
		// 创建一个新cURL资源
		$ch = curl_init();

		// 设置URL和相应的选项
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER , $header);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER , 1);

		// 抓取URL并把它传递给浏览器
		$dataStr = curl_exec($ch);

		// 关闭cURL资源，并且释放系统资源
		curl_close($ch);

		//返回
		return $dataStr;
	}
}
