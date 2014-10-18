<?php 
@header('Content-Type: text/html; charset=utf-8');
if ( $_GET['startStation'] && $_GET['arriveStation'])
{
	require 'chepiao100.php';
	$cp = new chepiao100();
	$data = $cp->getData('qichepiao', $_GET);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="css.css"/>
<title>汽车票查询</title>
</head>
<body>
<form id="get" name="get" action="" method="get">
<?php require 'header.php';?>
<div>发站：<input name="startStation" type="text" id="startStation" value="<?php echo $_GET['startStation'] ? $_GET['startStation']: '上海'; ?>"/></div>
<div>到站：<input name="arriveStation" type="text" id="arriveStation" value="<?php echo $_GET['arriveStation'] ? $_GET['arriveStation']: '南通'; ?>"/></div>
<BR />
<div><input type="submit" value=" 提交查询 " class="button"/></div>
</div>
</div>
</form>
<BR />
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr class="head">
<td>&nbsp;</td>
<td>出发站</td>
<td>目的地</td>
<td>发车时间</td>
<td>参考票价</td>
<td>车型</td>
<td>&nbsp;</td>
</tr>
<?php if (count($data) > 0) :?>
<?php foreach($data as $val): ?>
<tr class="item">
<td>&nbsp;</td>
<td>
<a href="http://ditu.google.cn/maps?q=<?php echo urlencode($startStation.str_replace($startStation, '', $val['startStaion'])); ?>" target="_blank">
<?php echo $val['address']; ?><span sort="trainCode"><?php echo $val['startStaion']; ?></span></a>
</td>
<td><?php echo $_GET['arriveStation']; ?></td>
<td sort="startTime"><?php echo $val['startTime']; ?></td>
<td sort="endTime">￥<?php echo $val['price']; ?></td>
<td><?php echo $val['grade']; ?></td>
<td>&nbsp;</td>
</tr>
<?php endforeach; ?>
<?php endif; ?>
</table>
</body>
</html>
