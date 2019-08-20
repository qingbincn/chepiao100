<?php 
session_start();
header('Content-Type: text/html; charset=utf-8');
require 'model/TrainApi.php';
error_reporting(E_ALL^E_NOTICE);
?>
<!DOCTYPE>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>12306订票接口(v3.0)</title>
<link rel="stylesheet" type="text/css" href="css/style.css" />
<link type="text/css" href="plugins/validate/css/screen.css" rel="stylesheet"/>
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="http://v3.chepiao100.com/script.js"></script>
<script type="text/javascript" src="plugins/validate/jquery.validate.js"></script>
<script type="text/javascript" src="plugins/validate/localization/messages_zh.js"></script>
</head>
<body>
<div class="header wrap1200">
12306订票接口(v3.0) | 
<?php if($_SESSION['username']): ?>
用户：<a href="userinfo.php"><?php echo $_SESSION['username']; ?></a>
<?php else: ?>
<a href="login.php">会员登录</a>
<?php endif; ?>
<div class="menu">
<a href="ticket.php">车票预定</a>
<a href="passenger.php">乘客管理</a>
<a href="order.php">订单管理</a>
<a href="agency.php">代售点查询</a>
<a href="dynamic.php">正晚点查询</a>
<a href="logout.php">系统退出</a>
&nbsp;|
<a href="bus.php">汽车票查询</a>
&nbsp;|
<a href="https://github.com/phpbin/chepiao100" target="_blank">接口文档</a>
<a href="contact.php">联系我们</a>
</div>
</div>