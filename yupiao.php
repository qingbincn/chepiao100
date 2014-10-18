<?php 
@header('Content-Type: text/html; charset=utf-8');
if ( $_GET['startStation'] && $_GET['arriveStation'] && $_GET['date'])
{
	require 'chepiao100.php';
	$cp = new chepiao100();
	$data = $cp->getData('yupiao', $_GET);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="css.css"/>
<title>火车余票查询</title>
</head>
<body>
<form id="get" name="get" action="" method="get">
<?php require 'header.php';?>
<div>发站：<input name="startStation" type="text" id="startStation" value="<?php echo $_GET['startStation'] ? $_GET['startStation']: '上海'; ?>"/></div>
<div>到站：<input name="arriveStation" type="text" id="arriveStation" value="<?php echo $_GET['arriveStation'] ? $_GET['arriveStation']: '北京'; ?>"/></div>
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
<td>天数</td>
<td>价格</td>
<td>余票</td>
<td>&nbsp;</td>
</tr>
<?php if (count($data) > 0) :?>
<?php foreach($data as $val): ?>
<tr class="item">
		<td>&nbsp;</td>
		<td><?php echo $val['trainCode']; ?></td>
		<td><?php echo $val['trainGrade']; ?></td>
		<td><?php echo str_replace(array('shi','guo','zhong'), array('[始]','[过]','[终]'), $val['startType']).$val['startStation']; ?></td>
		<td><?php echo str_replace(array('shi','guo','zhong'), array('[始]','[过]','[终]'), $val['arriveType']).$val['arriveStation']; ?></td>
		<td><?php echo $val['startTime']; ?></td>
		<td><?php echo $val['endTime']; ?></td>
		<td><?php echo $val['takeTime']; ?></td>
		<td><?php echo $val['day_diff']; ?></td>
		<td>
		<?php if($val['business']!='--'): ?>
			<div>商务座: <?php echo $val['business-prc']; ?></div>
			<?php endif; ?>
			<?php if($val['best-seat']!='--'): ?>
			  <div>特等座：<?php echo $val['best-seat-prc']; ?></div>
			<?php endif; ?>
			<?php if($val['one-seat']!='--'): ?>
			  <div>一等座：<?php echo $val['one-seat-prc']; ?></div>
			<?php endif; ?>
			<?php if($val['two-seat']!='--'): ?>
			  <div>二等座：<?php echo $val['two-seat-prc']; ?></div>
			<?php endif; ?>
			<?php if($val['vag-sleeper']!='--'): ?>
			  <div>高级软卧：<?php echo $val['vag-sleeper-prc']; ?></div>
			<?php endif; ?>
			<?php if($val['soft-sleeper']!='--'): ?>
			  <div>软　卧：<?php echo $val['soft-sleeper-prc']; ?></div>
			<?php endif; ?>
			<?php if($val['hard-sleeper']!='--'): ?>
			  <div>硬　卧：<?php echo $val['hard-sleeper-prc']; ?></div>
			<?php endif; ?>
			<?php if($val['soft-seat']!='--'): ?>
			  <div>软　座：<?php echo $val['soft-seat-prc']; ?></div>
			<?php endif; ?>
			<?php if($val['hard-seat']!='--'): ?>
			  <div>硬　座：<?php echo $val['hard-seat-prc']; ?></div>
			<?php endif; ?>
			<?php if($val['none-seat']!='--'): ?>
			  <div>无　座：<?php echo $val['two-seat-prc'] ? $val['two-seat-prc'] : $val['hard-seat-prc']; ?></div>
			<?php endif; ?>
		 </td>
		<td>
			<?php if($val['business']!='--'): ?>
			  <div class="org"><i class="bg yu"></i><?php echo $val['business']; ?>张</div>
			<?php endif; ?>
			<?php if($val['best-seat']!='--'): ?>
			  <div class="org"><i class="bg yu"></i><?php echo $val['best-seat']; ?>张</div>
			<?php endif; ?>
			<?php if($val['one-seat']!='--'): ?>
			  <div class="org"><i class="bg yu"></i><?php echo $val['one-seat']; ?>张</div>
			<?php endif; ?>
			<?php if($val['two-seat']!='--'): ?>
			  <div class="org"><i class="bg yu"></i><?php echo $val['two-seat']; ?>张</div>
			<?php endif; ?>
			<?php if($val['vag-sleeper']!='--'): ?>
			  <div class="org"><i class="bg yu"></i><?php echo $val['vag-sleeper']; ?>张</div>
			<?php endif; ?>
			<?php if($val['soft-sleeper']!='--'): ?>
			  <div class="org"><i class="bg yu"></i><?php echo $val['soft-sleeper']; ?>张</div>
			<?php endif; ?>
			<?php if($val['hard-sleeper']!='--'): ?>
			  <div class="org"><i class="bg yu"></i><?php echo $val['hard-sleeper']; ?>张</div>
			<?php endif; ?>
			<?php if($val['soft-seat']!='--'): ?>
			  <div class="org"><i class="bg yu"></i><?php echo $val['soft-seat']; ?>张</div>
			<?php endif; ?>
			<?php if($val['hard-seat']!='--'): ?>
			  <div class="org"><i class="bg yu"></i><?php echo $val['hard-seat']; ?>张</div>
			<?php endif; ?>
			<?php if($val['none-seat']!='--'): ?>
			  <div class="org"><i class="bg yu"></i><?php echo $val['none-seat']; ?>张</div>
			<?php endif; ?>
	</td>
	<td>&nbsp;</td>
</tr>
<?php endforeach; ?>
<?php endif; ?>
</table>

</body>
</html>
