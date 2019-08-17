<?php require 'header.php';?>
<?php require 'session.php';?>
<?php
$param = [
    'token' => $_SESSION['token'],
    'where' => $_GET['where'] ?: "G",
    'start_date' => $_GET['start_date'] ?: date('Y-m-d', strtotime('-1month')),
    'end_date' => $_GET['end_date'] ?: date('Y-m-d', strtotime('+1month')),
];
$train = new TrainApi();
$train->method('train.order.query');
$data = $train->action($param);
$orderData = $data['datas'];
?>
<script type="text/javascript" src="plugins/confirm/js/dialog.js"></script>
<link rel="stylesheet" href="plugins/confirm/css/dialog.css" />

<div class="form wrap1200">
    <h1>已完成订单: <a href="order.php">已完成订单</a><a href="order-no.php">未完成订单</a></h1>
       <div class="search">
       <form id="postForm" name="postForm" method="get" action="order.php">
                       类型：
        <select name="where" id="where">
            <option value="G" <?php if($param['where']=="G") echo 'selected';?>>未出行订单</option>
            <option value="H" <?php if($param['where']=="H") echo 'selected';?>>历史订单</option>
        </select>
        <form id="postForm" name="postForm" method="get" action="order.php">
                         日期：
        <input type="text" id="start_date" name="start_date" value="<?php echo $param['start_date']; ?>" />
        --
        <input type="text" id="end_date" name="end_date" value="<?php echo $param['end_date']; ?>" />
        <input type="submit" value="搜索"/>
        </form>
      </div>
    <?php if ($data['errMsg'] == 'Y') : ?>
    <table width="100%" height="0" border="0" cellpadding="0" cellspacing="0" style="margin-top:10px;">
       <tr>
	      <th>订单号</th>
          <th>车次信息</th>
		  <th>席位信息</th>
		  <th>旅客信息</th>
		  <th>票款金额</th>
		  <th>车票状态</th>
		  <th width="40px">操作</th>
	   </tr
      <tbody>
      <?php foreach ($orderData as $order): ?>
        <tr>
          <td><?php echo $order['sequence_no']; ?></td>
          <td>
           <?php echo $order['train_date'].' '.$order['start_time']; ?>开<br/>
           <?php echo $order['train_code']; ?> <?php echo $order['from_station']; ?>-<?php echo $order['to_station']; ?><br/>
          </td>
          <td>
          	<?php foreach ($order['tickets'] as $val) : ?>
          		<?php echo $val['coach_name']; ?>车 <br/>
          		<?php echo $val['seat_name'];?><br/>
          		<?php echo $val['seat_type_name'];?><br/>
          	<?php endforeach;?>
          </td>
          <td>
          	<?php foreach ($order['tickets'] as $val) : ?>
          		<?php echo $val['passenger_name']; ?> <br/>
          		<?php echo $val['passenger_id_type_name'];?><br/>
          		<?php echo $val['passenger_id_no'];?><br/>
          	<?php endforeach;?>
          </td>
          <td>
          	<?php foreach ($order['tickets'] as $val) : ?>
          		<?php echo $val['ticket_type_name'];?><br/>
          		<?php echo $val['ticket_price'];?>元<br/>
          	<?php endforeach;?>
          </td>
          <td>
          	<?php foreach ($order['tickets'] as $val) : ?>
          		<?php echo $val['status_name'];?><br/>
          	<?php endforeach;?>
          </td>
          <td>
          	<?php foreach ($order['tickets'] as $val) : ?>
          		<?php if ($val['cancel_token']): ?>
          		<a href="javascript:doOrderCanel('<?php echo $val['cancel_token']; ?>', '<?php echo $val['sequence_no']; ?>');">退票</a><br/>
           		<div class="dialog-box-<?php echo $val['sequence_no']; ?>" style="display:none;"></div><br/>
          		<?php endif; ?>
          	<?php endforeach;?>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
    <?php else: ?>
    <div class="errMsg"><?php echo $data['errMsg']; ?></div>
    <?php endif; ?>
</div>
<link type="text/css" href="plugins/datepicker/css/foundation-datepicker.css" rel="stylesheet"/>
<script type='text/javascript' src='plugins/datepicker/js/foundation-datepicker.js' charset="utf-8"></script>
<script type='text/javascript' src='plugins/datepicker/js/locales/foundation-datepicker.zh-CN.js' charset="utf-8"></script>
<script type="application/javascript">
$('#start_date, #end_date').fdatepicker({format: 'yyyy-mm-dd'});


var doOrderCanel = function(cancel_token, sequence_no) {
	$.post('order-cancel.php?action=affirm', {cancel_token:cancel_token}, function(data){
	    data = JSON.parse(data);
		if (data.errMsg == 'Y') {
		    doOrderDialog(data.datas, sequence_no); 
		} else {
		
		}
	})
}
var doOrderDialog = function(data, sequence_no) {
	var html = '<div class="dailogQueue" id="dailogQueue">';
    html += '<div class="confirm"><span>您确认要退票吗？<br/> 共计退款： <i>'+data.return_price+'</i>元</span> <br>';
	html += '车票票款：'+data.ticket_price+'元 , 退票费：当前时间按<i>'+data.return_rate+'%</i> 核收, 计'+data.return_cost+'元<br/>应退票款：'+data.return_price+'元<br/>';
	html += '实际核收退票费及应退票款将按最终交易时间计算</div> ';
	html += '</div>';
	var $d = $(".dialog-box-"+sequence_no);
	$d.dialog({
		title: '交易提示',
		dragable:true,
		html: '', 
		width: 460,	
		height: 250,
		cannelText: '取消',
		confirmText: '确认',	
		showFooter:true,
		onOpen: false,
		onConfirm: function() {
			 location.href = 'order-cancel.php?action=refund';
		},
		getContent:function(){
			$d.find('.body-content').html(html);
		}
	}).open();
}
</script>
<?php require 'bottom.php';?>