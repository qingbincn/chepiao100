<?php
/**
 * 车票100 接口类
 * 
 * @author chepiao100
 *
 */
require 'config.php';
class chepiao100
{	
	/**
	 * 接口地址
	 * @var string
	 */
	private $_apiurl = 'http://www.chepiao100.com/api/';
		
  /**
   * 返回接口数据
   * 
   * @param string $method 接口方法
   * @param array $param 请求参数
   * @return mixed
  */
  function getData($method, $param)
  {
    global $config;
    $param['userid'] = $config['userid'];
    $param['seckey'] = $config['seckey'];
    $post = http_build_query($param);
    $html = $this->fetch_html($this->_apiurl.$method, $post);
    $jsonArr = json_decode($html, TRUE);
    if ( $jsonArr['errMsg'] == 'Y') {
      return $jsonArr['data'];
    } else {
   	  return $jsonArr['errMsg'];
    }
  }
 
  /**
   * 请求HTTP
   * 
   * @param string $url
   * @param string $post
   * @return mixed
   */
  function fetch_html($url, $post)
  {
	  $ch = curl_init($url);
	  curl_setopt($ch, CURLOPT_TIMEOUT, 60);
	  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	  curl_setopt($ch, CURLOPT_POST, true);
	  //curl_setopt($ch, CURLOPT_PROXY, 'http://10.100.10.100:3128');
	  curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	  $html = curl_exec($ch);
	  curl_close($ch);
	  return $html;
  }
}
/** End class of chepiao100 **/