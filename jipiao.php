<?php 
@header('Content-Type: text/html; charset=utf-8');
if ( $_GET['departureAirport'] && $_GET['arrivalAirport'] && $_GET['date'])
{
	require 'chepiao100.php';
	$cp = new chepiao100();
	$data = $cp->getData('jipiao', $_GET);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="css.css"/>
<title>机票查询</title>
</head>
<body>
<form id="get" name="get" action="" method="get">
<?php require 'header.php';?>
<div>超飞：<input name="departureAirport" type="text" id="departureAirport" value="<?php echo $_GET['departureAirport'] ? $_GET['departureAirport']: '上海'; ?>"/></div>
<div>降落：<input name="arrivalAirport" type="text" id="arrivalAirport" value="<?php echo $_GET['arrivalAirport'] ? $_GET['arrivalAirport']: '济南'; ?>"/></div>
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
<td>航空公司</td>
<td>航班号</td>
<td>机型</td>
<td>起飞时间</td>
<td>降落时间</td>
<td>起飞机场</td>
<td>降落机场</td>
<td>飞行时间</td>
<td>标准价格</td>
<td>准点率</td>
<td>&nbsp;</td>
</tr>
<?php if (count($data) > 0) :?>
<?php foreach($data as $val): ?>
<tr class="item">
<td>&nbsp;</td>
<td><?php echo $val['carrierCom']; ?></td>
<td><?php echo $val['flightCode']; ?></td>
<td><?php echo $val['planeType'].'('.$val['planeSize'].')'; ?></td>
<td><?php echo $val['departureTime']; ?></td>
<td><?php echo $val['arrivalTime']; ?></td>
<td><?php echo $val['departureAirport']; ?></td>
<td><?php echo $val['arrivalAirport']; ?></td>
<td><?php echo $val['costTime']; ?></td>
<td><?php echo $val['planeMemo']; ?></td>
<td><?php echo $val['correctness']; ?></td>
</tr>
<?php endforeach; ?>
<?php endif; ?>
</table>
</body>
</html>
