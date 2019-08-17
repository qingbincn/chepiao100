<?php require 'header.php';?>
<?php require 'session.php';?>
<?php
$train = new TrainApi();
$train->method('train/order/unpaid');
$data = $train->action(array('token'=>$_SESSION['token']));
$order = $data['datas'];
?>
<script type="text/javascript" src="plugins/confirm/js/dialog.js"></script>
<link rel="stylesheet" href="plugins/confirm/css/dialog.css" />

<div class="form wrap1200">
    <h1>未完成订单: <a href="order.php">已完成订单</a><a href="order-no.php">未完成订单</a></h1>
    <?php if ($data['errMsg'] == 'Y') : ?>
    <div class="block">
       席位已锁定，请在 <span>30</span> 分钟内进行支付，完成网上购票。 支付剩余时间：<span id="ShowTime" class="ShowTime"></span>
    </div>
    <table width="100%" height="0" border="0" cellpadding="0" cellspacing="0" style="margin-top:10px;">
       <tr>
	      <th>订单号</th>
          <th>车次信息</th>
		  <th>席位信息</th>
		  <th>旅客信息</th>
		  <th>票款金额</th>
		  <th width="90px">状态/关闭时间</th>
	   </tr
      <tbody>
      	<?php if($order) : ?>
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
          		<?php echo $val['seat_type_name'];?>
          	<?php endforeach;?>
          </td>
          <td>
          	<?php foreach ($order['tickets'] as $val) : ?>
          		<?php echo $val['passenger_name']; ?> <br/>
          		<?php echo $val['passenger_id_type_name'];?><br/>
          		<?php echo $val['passenger_id_no'];?>
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
          		<?php echo $val['lose_time'];?><br/>
          		
          	<?php endforeach;?>
          </td>
        </tr>
        <?php endif; ?>
      </tbody>
    </table>
    <div class="orderBtn">
        <a href="order-cancel.php?action=cancel&sequence_no=<?php echo $order['sequence_no']; ?>" onClick="return doDelete();" class="cannel">取消</a>
        <a href="javascript:doPayment('pay-gateway.php?sequence_no=<?php echo $order['sequence_no']; ?>')" class="confirm">支付</a>
    </div>
    <?php else: ?>
    <div class="errMsg"><?php echo $data['errMsg']; ?></div>
    <?php endif; ?>
</div>
<div class="dialog-box" style="display:none;"></div>
<script>
$(function(){
   countDown("<?php echo $order['count_down']; ?>");
});

var doPayment = function(url)
{
	var html = '<div class="dailogQueue" id="dailogQueue">';
	html += '<div class="loading"></div>';
	html += '<div class="content">';
	html += '支付完成前，请不要关闭此支付验证窗口。<br/>支付完成后，请根据您支付的情况点击下面按钮。';
	html += '</div>';
	html += '</div>';
   	var $d = $(".dialog-box");
	$d.dialog({
		title: '网上支付提示',	
		dragable:true,
		html: '', 						
		width: 500,					
		height: 200,				
		cannelText: '支付遇到问题',		
		confirmText: '支付完成',	
		showFooter:true,
		onClose: function(){
		  location.href = 'order-no.php';
		},
		onOpen: false,		
		onConfirm: function() {
			 location.href = 'order-no.php';
		},
		onCannel: function(){  	
			location.href = 'order-no.php';
		},
		getContent:function(){
			$d.find('.body-content').html(html);
		}
	}).open(); 
   
   window.open(url);
}

// 倒计时
var countDown = function(time) {
	time = parseInt(time);
	var m = Math.floor(time / 60);
	var s = Math.floor(time % 60);
	
	if (m < 10) {
	  m = "0" + m
	}
	if (s < 10) {
	  s = "0" + s
	}
	if (time > 0) {
	  timeMsg = "<strong>" + m + "分" + s + "秒</strong>"
	} else {
	  timeMsg = '<strong><font color="#FF0000">00秒</font></strong>';
	}
	
	time = time - 1;
	if (time >= 0) {
	  window.setTimeout('countDown("' + time + '")', 1000)
	} else {
	  timeMsg = '<strong><font color="#FF0000">00秒</font></strong>';
	  $('.orderBtn, .block').remove();
	}
	$("#ShowTime").html(timeMsg);
}

var doDelete = function()
{
  if (confirm('您确认取消订单吗？ \n 一天内3次申请车票成功后取消订单，当日将不能在网站购票。') == false){
    return false;
  }
}
</script>
<?php require 'bottom.php';?>