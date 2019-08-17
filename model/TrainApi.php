<?php
error_reporting(E_ALL^E_NOTICE);
date_default_timezone_set('Asia/Shanghai');

/**
 * 接口处理类
 * 测试版：appKey & appSecret 可先填
 * 
 * @author phpbin
 *
 */
class TrainApi
{
  
  /**
   * 授权ID
   * 
   * @var string
   */
   public $ACCESSID = '10000';
   
   /**
    * 授权KEY
    * 
    * @var string
    */
   public $ACCESSKEY = '9f6e6800cfae7749eb6c486619254b9c';
   
   /**
    * 接口地址
    * 
    * @var string
    */
   public $API = 'http://v3.chepiao100.com/v30.api';
   //public $API = 'http://www.ji.com/chepiaov3/v30.api';
   
   /**
    * 设置方法
    * 
    * @param string $method
    */
   public function method($method)
   {
     $method = strtolower($method);
     $this->ACCESSIP = $_SERVER['REMOTE_ADDR'];
     $this->API = $this->API.'?do='.$method.'&_'.rand(10000, 99999);     
   }
   
   /**
    * 接口请求
    * 
    * @param array $param
    * @return mixed
    */
   public function action($param = '')
   {
     $json = $this->http($this->API, $param);
     return json_decode($json, true);
   }
   
   /**
    * 取数据
    * 
    * @param string $url
    * @param array $post
    * @return mixed
    */
   private function http($url, $post = '')
   {
     $header = array(
         'Cache-Control:nocache',
         'Pragma:no-cache',
         'Expires:-1',
         'ACCESSID: '.$this->ACCESSID,
         'ACCESSKEY:'.$this->ACCESSKEY
     );
     $ch = curl_init($url);
     curl_setopt($ch, CURLOPT_TIMEOUT, 60);
     curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
     curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
     
     if (!empty($post)) {
       curl_setopt($ch, CURLOPT_POST, true);
       curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
     }
     $html = curl_exec($ch);
     curl_close($ch);
     
     // ==== 保存日志 =================================
     $this->debug($url, $post, $html);
     //============================================
     
     return $html;
   }
      
   /**
    * 日志调试
    * 
    */
   public function debug(...$params)
   {
     ob_start();
     echo "\n";
     echo "-------------------------------------------------------------\n";
     foreach ($params as $param) {
       if (is_string($param) && stripos($param, '<!DOCTYPE') !== false) continue;
       if (is_string($param) && stripos($param, 'JFIF') !== false) continue;
       var_dump($param);
     }
     echo "-------------------------------------------------------------\n";
     $_debug = ob_get_contents();
     ob_end_clean();
     error_log($_debug, 3, 'logs/'.date('Ymd').'.log');
   }
}

// 调试方法
function dv($params)
{
    echo '<pre>';
    var_dump($params);
    exit();
}