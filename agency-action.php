<?php 
session_start();
set_time_limit(1200);
header('Content-Type: text/html; charset=utf-8');
require 'model/TrainApi.php';

// 城市
if ($_GET['action'] == 'city') {
  
  $param = $_POST;
  $train = new TrainApi();
  $train->method('train.agency.city');
  $data = $train->action($param);
  $html = '<option value="">选择城市</option>';
  foreach ($data['datas'] as $name){
    $html .= '<option value="'.$name.'">'.$name.'</option>';
  }
  echo $html;
  exit();
}

// 县区
if ($_GET['action'] == 'county') {
  $param = $_POST;
  $train = new TrainApi();
  $train->method('train.agency.county');
  $data = $train->action($param);
  $html = '<option value="">选择县区</option>';
  foreach ($data['datas'] as $name){
    $html .= '<option value="'.$name.'">'.$name.'</option>';
  }
  echo $html;
  exit();
}

// 代售点
if ($_GET['action'] == 'query') {
  $param = $_POST;
  $train = new TrainApi();
  $train->method('train.agency.query');
  $data = $train->action($param);
?>
  <?php if (is_array($data['datas'])):?>
  <table width="100%" height="0" border="0" cellpadding="0" cellspacing="0" style="margin-top:10px;">
    <tr>
	    <th width="40px">序号</th>
		  <th>代售点名称</th>
		  <th>地址</th>
		  <th>营业时间</th>
       <th width="60px;">窗口</th>
	   </tr>
       <?php foreach ($data['datas'] as $key=>$val): ?>
       <tr>
         <td><?php echo $key+1; ?></td>
         <td><?php echo $val['agency_name']; ?></td>
         <td><?php echo $val['address']; ?></td>
         <td style="padding-right:20px;"><?php echo $val['morning'].' <br/> '.$val['afternoon']; ?></td>
         <td><?php echo $val['windows']; ?></td>
       </tr>
       <?php endforeach; ?>
  </table>
  <?php endif; ?>
<?php 
}
?>