<?php require 'header.php';?>
<?php
if (!empty($_GET['from_station'])&&!empty($_GET['to_station'])&&!empty($_GET['train_date'])){
    $param = $_GET;
    $train = new TrainApi();
    $train->method('train.ticket.query');
    $data = $train->action($param);
}

?>
<link type="text/css" href="plugins/datepicker/css/foundation-datepicker.css" rel="stylesheet"/>
<script type='text/javascript' src='plugins/datepicker/js/foundation-datepicker.js' charset="utf-8"></script>
<script type='text/javascript' src='plugins/datepicker/js/locales/foundation-datepicker.zh-CN.js' charset="utf-8"></script>

<link type="text/css" rel="stylesheet" href="plugins/complete/css/jquery.autocomplete.css" />
<script type="text/javascript" src="plugins/complete/js/station_name.js"></script>
<script type="text/javascript" src="plugins/complete/js/jquery.autocomplete.js"></script>

<link type="text/css" href="plugins/jtip/css/global.css" rel="stylesheet"/>
<script type="text/javascript" src="plugins/jtip/js/jtip.js"></script>

<div class="form wrap1200">
   <h1>车票预定：</h1>
   <div class="errMsg"><?php if($data['errMsg']!='Y') echo $data['errMsg']; ?></div>
   <div class="search">
    <form id="postForm" name="postForm" method="get" action="ticket.php">
    出发站：<input type="text" id="from_station" name="from_station" value="<?php echo $_GET['from_station']; ?>" class="station"/>
    目的地：<input type="text" id="to_station" name="to_station" value="<?php echo $_GET['to_station']; ?>" class="station"/>
    出发日：<input type="text" id="train_date" name="train_date" value="<?php echo $_GET['train_date']; ?>" />
    <input type="submit" value="搜索"/>
    </form>
  </div>
  <?php if (is_array($data['datas'])):?>
  <table width="100%" height="0" border="0" cellpadding="0" cellspacing="0" style="margin-top:10px;">
       <tr>
	      <th>车次</th>
		  <th>出发站<br/>到达站</th>
		  <th>出发时间<br/>到达时间</th>
		  <th>余票</th>
          <th>价格</th>
		  <th width="80px">备注</th>
	   </tr>
       <?php foreach ($data['datas'] as $key=>$val): ?>
       <tr>
         <td>
         <a href="ticket-time.php?time_token=<?php echo $val['time_token']; ?>&width=450" class="jTip" id="T_<?php echo $key; ?>" name="车次">
		 <?php echo $val['train_code']; ?></a></td>
         </td>
         <td><?php echo $val['from_station'].'<BR/>'.$val['to_station']; ?></td>
         <td><?php echo $val['start_time'].'<BR/>'.$val['arrive_time']; ?></td>
         <td>
           <?php foreach ($val['yp_info'] as $yp): ?>
           <?php echo $yp['na'].':'.$yp['yp'];?><br/>
           <?php endforeach; ?>
         </td>
         <td>
           <?php foreach ($val['yp_info'] as $yp): ?>
           <?php echo '￥'.$yp['pr'].'元';?><br/>
           <?php endforeach; ?>
         </td>
         <td>
           <?php if ($val['can_buy'] == 'Y'): ?>
             <a href="javascript:doOrderBook('<?php echo $val['order_token']; ?>');" class="bookBtn"><?php echo $val['button_text']; ?></a>
           <?php else: ?>
              <div class="message">
                 <?php echo $val['button_text']; ?>
             </div>
           <?php endif; ?>
         </td>
       </tr>
       <?php endforeach; ?>
  </table>
  <? else: ?>
  <?php endif; ?>
</div>
<form id="submitForm" name="submitForm" method="post" action="order-booking.php">
<input type="hidden" name="from_station" value="<?php echo $_GET['from_station']; ?>" class="station"/>
<input type="hidden" name="to_station" value="<?php echo $_GET['to_station']; ?>" class="station"/>
<input type="hidden" name="train_date" value="<?php echo $_GET['train_date']; ?>" />
<input type="hidden" name="order_token" id="order_token" value="" />
</form>

<script type="text/javascript">
 $('#train_date').fdatepicker({format: 'yyyy-mm-dd'});
 $(function(){
	 
   $(".station").autocomplete(station_array, {
		minChars: 0,
		width: 200,
		max: 10,
		scrollHeight: 300,
		matchContains: true,
		autoFill: false,
		formatItem: function(data) 
		{
		    var temp = data[0].split("|");
			return temp[1] + "(" + temp[0] + ")";
		},
		formatMatch: function(data) 
		{
		    var temp = data[0].split("|");
			return temp[1] + "(" + temp[0] + ")";
		},
		formatResult: function(data) 
		{
			return data[0].split("|")[1];
		}
	}).result(function(event, data, formatted) {
	    var hidden = $(this).parent().next().find(">:input");
	    hidden.val( (hidden.val() ? hidden.val() + ";" : hidden.val()));
	    $("#loading").hide();
  });
	 
   $("#postForm").validate({ 
	    errorElement: "em",
	    errorPlacement: function(error, element) {
			var this_id = element.attr('id');
	        $(".vaildTip").html('');
	        error.appendTo( $(".vaildTip") );
	    },
	    success: function(label) {
	        label.text("通过信息验证！").addClass("success");
	    },
        rules: {
           from_station: {required: true},
		   to_station: {required: true},
		   train_date: {required: true}
        }
  });
});

// 表单提交
var doOrderBook = function(token) {
    $('#order_token').val(token);
	$('#submitForm').submit();
}
</script>
<?php require 'bottom.php';?>