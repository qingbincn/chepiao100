<?php 
session_start();
header('Content-Type: text/html; charset=utf-8');
require 'model/TrainApi.php';
require 'session.php';
$train = new TrainApi();
$train->method('train.passenger.delete');
$param = $_GET;
$param['token'] = $_SESSION['token'];
$data = $train->action($param);

if ( $data['errMsg'] == 'Y'){
  echo '<script>alert("乘客删除成功！");location.href = "passenger.php"; </script>';
} else {
  echo '<script>alert("'.$data['errMsg'].'");location.href = "passenger.php"; </script>';
}

