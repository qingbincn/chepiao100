<?php 
@header('Content-Type: text/html; charset=utf-8');
if ( $_GET['stationName'])
{
	require 'chepiao100.php';
	$cp = new chepiao100();
	$data = $cp->getData('chezhan', $_GET);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="css.css"/>
<title>火车车站查询</title>
</head>
<body>
<form id="get" name="get" action="" method="get">
<?php require 'header.php';?>
<div>车站：<input name="stationName" type="text" id="stationName" value="<?php echo $_GET['stationName'] ? $_GET['stationName']: '上海'; ?>"/></div>
<div>日期：<input name="date" type="text" id="date" value="<?php echo $_GET['date'] ? $_GET['date']: date('Y-m-d', strtotime('+ 1 day'))?>"/></div>
<BR />
<div><input type="submit" value=" 提交查询 " class="button"/></div>
</div>
</div>
</form>
<BR />
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr class="head">
<td>&nbsp;</td>
<td>车次</td>
<td>类型</td>
<td>发站</td>
<td>到站</td>
<td>发时</td>
<td>到时</td>
<td>运行时间</td>
<td>里程</td>
<td>价格</td>
<td>&nbsp;</td>
</tr>
<?php if (count($data) > 0) :?>
	<?php foreach($data as $val): ?>
	<tr class="item">
	  <td>&nbsp;</td>
	  <td><?php echo preg_replace('/\/.*$/is', '', $val['trainCode']); ?></td>
	  <td><?php echo $val['trainGrade']; ?></td>
	  <td><?php echo str_replace(array('shi','guo','zhong'), array('[始]','[过]','[终]'), $val['startType']).$val['startStation']; ?></td>
	  <td><?php echo str_replace(array('shi','guo','zhong'), array('[始]','[过]','[终]'), $val['arriveType']).$val['arriveStation']; ?></td>
	  <td><?php echo $val['startTime']; ?></td>
	  <td><?php echo $val['endTime']; ?></td>
	  <td><?php echo $val['takeTime']; ?></td>
	  <td><?php echo $val['mileage']; ?></td>
	  <td>		
		<?php if($val['business-prc']): ?>
		  <div>商务座: ¥<?php echo $val['business-prc']; ?></div>
		<?php endif; ?>
		<?php if($val['best-seat-prc']): ?>
		  <div>特等座：¥<?php echo $val['best-seat-prc']; ?></div>
		<?php endif; ?>
		<?php if($val['one-seat-prc']): ?>
		  <div>一等座：¥<?php echo $val['one-seat-prc']; ?></div>
		<?php endif; ?>
		<?php if($val['two-seat-prc']): ?>
		  <div>二等座：¥<?php echo $val['two-seat-prc']; ?></div>
		<?php endif; ?>
		<?php if($val['vag-sleeper1-prc']): ?>
		  <div>高级软卧：¥<?php echo $val['vag-sleeper1-prc'].' / '.$val['vag-sleeper2-prc']; ?></div>
		<?php endif; ?>
		<?php if($val['soft-sleeper1-prc']): ?>
		  <div>软　卧：¥<?php echo $val['soft-sleeper1-prc'].' / '.$val['soft-sleeper2-prc']; ?></div>
		<?php endif; ?>
		<?php if($val['hard-sleeper1-prc']): ?>
		  <div>硬　卧：¥<?php echo $val['hard-sleeper1-prc'].' / '.$val['hard-sleeper2-prc'].' / '.$val['hard-sleeper3-prc']; ?></div>
		<?php endif; ?>
		<?php if($val['soft-seat-prc']): ?>
		  <div>软　座：¥<?php echo $val['soft-seat-prc']; ?></div>
		<?php endif; ?>
		<?php if($val['hard-seat-prc']): ?>
		  <div>硬　座：¥<?php echo $val['hard-seat-prc']; ?></div>
		<?php endif; ?>
		<?php if($val['none-seat-prc']): ?>
		  <div>无　座：¥<?php echo $val['two-seat-prc'] ? $val['two-seat-prc'] : $val['hard-seat-prc']; ?></div>
		<?php endif; ?>
		</td>
	 </tr>
	<?php endforeach; ?>
<?php endif;?>
</table>
</body>
</html>
