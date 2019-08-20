<?php
session_start();
header('Content-Type: text/html; charset=utf-8');
require 'model/TrainApi.php';
$param = array();
$param['time_token'] = $_GET['time_token'];
$train = new TrainApi();
$train->method('train.ticket.time');
$data = $train->action($param);
?>
<!DOCTYPE>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>12306订票</title>
<link rel="stylesheet" type="text/css" href="css/style.css" />
</head>
<body>
<div class="form wrap200">
<?php if ($data['errMsg'] == 'Y'): ?>
   <table width="100%" height="0" border="0" cellpadding="0" cellspacing="0">
      <tr>
         <th>站序</th>
         <th>站名</th>
         <th>到站时间</th>
         <th>出发时间</th>
         <th>停留时间</th>
      </tr>
      <?php foreach ($data['datas']['items'] as $val): ?>
      <tr>
        <td><?php echo $val['station_no']; ?></td>
        <td><?php echo $val['station_name']; ?></td>
        <td><?php echo $val['arrive_time']; ?></td>
        <td><?php echo $val['start_time']; ?></td>
        <td><?php echo $val['stopover_time']; ?></td>
      </tr>
      <?php endforeach;?>
   </table>
<?php else: ?>
   <div class="errMsg"><?php echo $data['errMsg']; ?></div>
<?php endif; ?>
   <h1>
     <?php echo $data['datas']['info']['train_code']; ?>&nbsp;&nbsp;
     <?php echo $data['datas']['info']['start_station']; ?>--<?php echo $data['datas']['info']['end_station']; ?>
     &nbsp;&nbsp;
     <?php echo $data['datas']['info']['train_class']; ?> 
     &nbsp;&nbsp;
              有空调
   </h1>
</body>
</html>
<?php require 'bottom.php';?>