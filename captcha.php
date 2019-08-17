<?php 
// 验证码
session_start();
error_reporting(E_ALL^E_NOTICE);

// 提取验证码
if ($_GET['action'] == 'dama') {
  echo $_SESSION['result'];
  unset($_SESSION['result']);
  exit();
}

//header('Content-type: image/jpg');
require 'model/TrainApi.php';
$module = $_GET['module'];

$param = array();
$param['token']   = $_SESSION['token'];
$param['module']  = $_GET['module'];

$train = new TrainApi();
$train->method('train.captcha.base64');
$data = $train->action($param);
echo base64_decode($data['datas']['base64']);
exit();

// 自动识别验证码
$dmParam = [
    'base64'   => $data['datas']['base64'], 
    'username' => 'suanyaios', 
    'password' => 'suanyaios'
];

$train = new TrainApi();
$train->method('train.ocr.yundama');
$data = $train->action($dmParam);


if ($data['errMsg'] == 'Y') {
    $_SESSION['answer'] =  $data['datas']['answer'];
    $_SESSION['result'] = $data['datas']['result'];
}