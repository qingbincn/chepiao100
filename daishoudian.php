<?php 
@header('Content-Type: text/html; charset=utf-8');
if ( $_GET['province'])
{
	require 'chepiao100.php';
	$cp = new chepiao100();
	$data = $cp->getData('daishoudian ', $_GET);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="css.css"/>
<title>火车票代售点查询</title>
</head>
<body>
<form id="get" name="get" action="" method="get">
<?php require 'header.php';?>
<div>省份：<input name="province" type="text" id="province" value="<?php echo $_GET['province']!="" ? $_GET['province']: '北京'; ?>"/></div>
<div>城市：<input name="city" type="text" id="city" value="<?php echo $_GET['city']!="" ? $_GET['city']: '北京'; ?>"/></div>
<div>县区：<input name="county" type="text" id="county" value="<?php echo $_GET['county']!="" ? $_GET['county']: '东城区'; ?>"/></div>
<BR />
<div><input type="submit" value=" 提交查询 " class="button"/></div>
</div>
</div>
</form>
<BR />
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr class="head">
<td>&nbsp;</td>
<td>序号</td>
<td>代售点名称</td>
<td>地址</td>
<td>营业时间</td>
<td>联系电话</td>
<td>&nbsp;</td>
</tr>
<?php if (count($data) > 0 && is_array($data)) :?>
<?php foreach($data as $val): ?>
<tr class="item">
<td>&nbsp;</td>
<td><?php echo $key+1; ?></td>
<td><?php echo $val['agency_name']; ?></td>
<td><a href="http://ditu.google.cn/maps?f=q&hl=zh-CN&q=<?php echo urlencode($val['address']); ?>" target="_blank">
<?php echo $val['address']; ?></a></td>
<td><?php echo $val['start_time_am'].'-'.$val['stop_time_pm']; ?></td>
<td><?php echo $val['phone_no']; ?></td>
<td>&nbsp;</td>
</tr>
<?php endforeach; ?>
<?php endif; ?>
</table>
</body>
</html>
