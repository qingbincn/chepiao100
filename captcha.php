<?php 
// 验证码
session_start();
error_reporting(E_ALL^E_NOTICE);
require 'model/TrainApi.php';

// 提取验证码
if ($_GET['action'] == 'dama') {
    if ($base64 = $_SESSION['base64']) {
        $param = [
            'base64'   => $base64,
            'username' => 'suanyaios',
            'password' => 'suanyaios'
        ];
        $train = new TrainApi();
        $train->method('train.ocr.yundama');
        $data = $train->action($param);
        if ($data['errMsg'] == 'Y') {
            echo $data['datas']['result'];
        }
    }
    exit();
}

header('Content-type: image/jpg');
$module = $_GET['module'];

$param = array();
$param['token']   = $_SESSION['token'];
$param['module']  = $_GET['module'];

$train = new TrainApi();
$train->method('train.captcha.base64');
$data = $train->action($param);

if ($data['errMsg'] == 'Y') {
    $base64 = $data['datas']['base64'];
    $_SESSION['base64'] = $data['datas']['base64'];
    echo base64_decode($base64);
} else {
    unset($_SESSION['base64']);
    echo file_get_contents('css/images/default.jpg');
    
}