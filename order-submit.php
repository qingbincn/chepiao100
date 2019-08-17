<?php 
session_start();
header('Content-Type: text/html; charset=utf-8');
require 'model/TrainApi.php';

// 校验订单
if ($_GET['action'] == 'checkOrder') {
  $param = $_POST;
  $param['token']             = $_SESSION['token'];
  $param['ticket_token']      = $param['ticket_token'];
  $param['choose_seat']       = $param['choose_seat'];
  $param['seat_type']         = implode(',', $param['seat_type']);
  $param['ticket_type']       = implode(',', $param['ticket_type']);
  $param['passenger_name']    = implode(',', $param['passenger_name']);
  $param['passenger_id_type'] = implode(',', $param['passenger_id_type_code']);
  $param['passenger_id_no']   = implode(',', $param['passenger_id_no']);
  $param['allEncStr']         = implode(',', $param['allEncStr']);
  $train = new TrainApi();
  $train->method('train.order.check');
  $data = $train->action($param);
  echo json_encode($data);
  exit();
}

// 余票队列
if ($_GET['action'] == 'queueCount') {
    $param = $_POST;
    $param['token']             = $_SESSION['token'];
    $param['ticket_token']      = $param['ticket_token'];
    $param['choose_seat']       = $param['choose_seat'];
    $param['seat_type']         = implode(',', $param['seat_type']);
    $param['ticket_type']       = implode(',', $param['ticket_type']);
    $param['passenger_name']    = implode(',', $param['passenger_name']);
    $param['passenger_id_type'] = implode(',', $param['passenger_id_type_code']);
    $param['passenger_id_no']   = implode(',', $param['passenger_id_no']);
    $param['allEncStr']         = implode(',', $param['allEncStr']);
  $train = new TrainApi();
  $train->method('train.order.queue');
  $data = $train->action($param);
  echo json_encode($data);
  exit();
}

// 确认提交
if ($_GET['action'] == 'confirm') {

    $param = $_POST;
    $param['token']             = $_SESSION['token'];
    $param['ticket_token']      = $param['ticket_token'];
    $param['choose_seat']       = $param['choose_seat'];
    $param['seat_type']         = implode(',', $param['seat_type']);
    $param['ticket_type']       = implode(',', $param['ticket_type']);
    $param['passenger_name']    = implode(',', $param['passenger_name']);
    $param['passenger_id_type'] = implode(',', $param['passenger_id_type_code']);
    $param['passenger_id_no']   = implode(',', $param['passenger_id_no']);
    $param['allEncStr']         = implode(',', $param['allEncStr']);
  $train = new TrainApi();
  $train->method('train.order.confirm');
  $data = $train->action($param);
  echo json_encode($data);
  exit();
}

// 订单等待
if ($_GET['action'] == 'waitTime') {
    $param = $_POST;
    $param['token']             = $_SESSION['token'];
    $param['ticket_token']      = $param['ticket_token'];
    
  $train = new TrainApi();
  $train->method('train.order.wait');
  $data = $train->action($param);
  echo json_encode($data);
  exit();
}

// 进入队列
if ($_GET['action'] == 'returnQueue') {
    $param = $_POST;
    $param['token']        = $_SESSION['token'];
    $param['sequence_no']  = $param['sequence_no'];
  $train = new TrainApi();
  $train->method('train.order.result');
  $data = $train->action($param);
  echo json_encode($data);
  exit();
}