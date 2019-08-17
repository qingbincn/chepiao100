<?php require 'header.php';?>
<?php
if (!empty($_GET['start_city'])&&!empty($_GET['end_city'])&&!empty($_GET['start_date'])){
  $param = $_GET;
  $train = new TrainApi();
  $train->method('train.bus.query');
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
   <h1>汽车票查询：</h1>
   <div class="errMsg"><?php if($data['errMsg']!='Y') echo $data['errMsg']; ?></div>
   <div class="search">
    <form id="postForm" name="postForm" method="get" action="bus.php">
    出发站：<input type="text" id="start_city" name="start_city" value="<?php echo $_GET['start_city']; ?>" class="station"/>
    目的地：<input type="text" id="end_city" name="end_city" value="<?php echo $_GET['end_city']; ?>" class="station"/>
    出发日：<input type="text" id="start_date" name="start_date" value="<?php echo $_GET['start_date']; ?>" />
    <input type="submit" value="搜索"/>
    </form>
  </div>
  <?php if (is_array($data['datas'])):?>
  <table width="100%" height="0" border="0" cellpadding="0" cellspacing="0" style="margin-top:10px;">
       <tr>
	      <th>出发站</th>
		  <th>到达站</th>
		  <th>出发时间</th>
          <th>汽车票价</th>
          <th>客车等级</th>
		  <th width="80px">备注</th>
	   </tr>
       <?php foreach ($data['datas'] as $key=>$val): ?>
       <tr>
         <td><?php echo $val['start_station']; ?></td>
         <td><?php echo $val['end_station']; ?></td>
         <td><?php echo $val['start_time']; ?></td>
         <td>￥<?php echo $val['bus_price']; ?></td>
         <td><?php echo $val['bus_class']; ?></td>
         <td>
         <?php echo $val['line_msg']; ?> <BR>
         <?php echo $val['overtime_msg']; ?>
         </td>
       </tr>
       <?php endforeach; ?>
  </table>
  <? else: ?>
  <?php endif; ?>
</div>

<script type="text/javascript">
 $('#start_date').fdatepicker({format: 'yyyy-mm-dd'});
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
    $('#submitToken').val(token);
	$('#submitForm').submit();
}
</script>
<?php require 'bottom.php';?>