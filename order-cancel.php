<?php
// 校验验证码
session_start();
header('Content-Type: text/html; charset=utf-8');
require 'model/TrainApi.php';

// 未完成订单取消
if ($_GET['action'] == 'cancel') {
  $param = array();
  $param['token'] = $_SESSION['token'];
  $param['sequence_no'] = $_GET['sequence_no'];
  
  $train = new TrainApi();
  $train->method('train.order.cancel');
  $data = $train->action($param);
  if ( $data['errMsg'] == 'Y'){
    echo '<script>alert("订单取消成功！");location.href = "order-no.php"; </script>';
  } else {
    echo '<script>alert("'.$data['errMsg'].'");location.href = "order-no.php"; </script>';
  }
  exit();
}

// 退票提醒
if ($_GET['action'] == 'affirm') {
  $param = array();
  $param['token'] = $_SESSION['token'];
  $param['cancel_token'] = $_POST['cancel_token'];
  
  $train = new TrainApi();
  $train->method('train.order.affirm');
  $data = $train->action($param);
  echo json_encode($data);
  exit();
}

// 确认退票
if ($_GET['action'] == 'refund') {
  $param = array();
  $param['token'] = $_SESSION['token'];

  $train = new TrainApi();
  $train->method('train.order.refund');
  $data = $train->action($param);

  if ( $data['errMsg'] == 'Y'){
    echo '<script>alert("退票成功！");location.href = "order.php"; </script>';
  } else {
    echo '<script>alert("'.$data['errMsg'].'");location.href = "order-no.php"; </script>';
  }
  exit();
}