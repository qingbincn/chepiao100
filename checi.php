<?php 
@header('Content-Type: text/html; charset=utf-8');
if ( $_GET['trainCode'])
{
	require 'chepiao100.php';
	$cp = new chepiao100();
	$data = $cp->getData('checi', $_GET);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="css.css"/>
<title>火车车次查询</title>
</head>
<body>
<form id="get" name="get" action="" method="get">
<?php require 'header.php';?>
<div>车次：<input name="trainCode" type="text" id="trainCode" value="<?php echo $_GET['trainCode'] ? $_GET['trainCode']: 'T110'; ?>"/></div>
<div>日期：<input name="date" type="text" id="date" value="<?php echo $_GET['date'] ? $_GET['date']: date('Y-m-d', strtotime('+ 1 day'))?>"/></div>
<BR />
<div><input type="submit" value=" 提交查询 " class="button"/></div>
</div>
</div>
</form>
<BR />
<?php if (isset($data['head'])) :?>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr class="head">
<td>&nbsp;</td>
<?php foreach($data['head'] as $key=>$val): ?>
<td><?php echo $val; ?></td>
<?php endforeach; ?>
</tr>
<?php foreach($data['item'] as $item): ?>
<tr class="item">
<td>&nbsp;</td>
<?php foreach($item as $key=>$val): ?>
<td><?php echo $val; ?></td>
<?php endforeach; ?>
</tr>
<?php endforeach; ?>
<?php endif; ?>
</body>
</html>
