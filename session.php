<?php
if (empty($_SESSION['token']) || empty($_SESSION['username'])) {
  header('location:login.php');
  exit();
}

$train = new TrainApi();
$train->method('train.user.auth');
$data = $train->action(array('token'=>$_SESSION['token']));
if ($data['errMsg'] !='Y') {
    unset($_SESSION['username'], $_SESSION['token']);
    header('location:login.php');
    exit();
}