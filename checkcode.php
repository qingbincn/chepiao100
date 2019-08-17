<?php
// 校验验证码
session_start();
header('Content-Type: text/html; charset=utf-8');
require 'model/TrainApi.php';

$param = array();
$param['token']  = $_SESSION['token'];
$param['answer'] = $_POST['answer'];
$param['module'] = $_GET['module'];

$train = new TrainApi();
$train->method('train.captcha.check');
$data = $train->action($param);
echo json_encode($data);
