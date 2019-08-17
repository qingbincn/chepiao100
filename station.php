<?php require 'header.php';?>
<?php
if (!empty($_GET['station_name'])){
  $param = $_GET;
  $train = new TrainApi();
  $train->method('train.ticket.station');
  $data = $train->action($param);
  
}

?>
<link type="text/css" href="plugins/datepicker/css/foundation-datepicker.css" rel="stylesheet"/>
<script type='text/javascript' src='plugins/datepicker/js/foundation-datepicker.js' charset="utf-8"></script>
<script type='text/javascript' src='plugins/datepicker/js/locales/foundation-datepicker.zh-CN.js' charset="utf-8"></script>

<link type="text/css" rel="stylesheet" href="plugins/complete/css/jquery.autocomplete.css" />
<script type="text/javascript" src="plugins/complete/js/station_name.js"></script>
<script type="text/javascript" src="plugins/complete/js/code_name.js"></script>
<script type="text/javascript" src="plugins/complete/js/jquery.autocomplete.js"></script>

<link type="text/css" href="plugins/jtip/css/global.css" rel="stylesheet"/>
<script type="text/javascript" src="plugins/jtip/js/jtip.js"></script>

<div class="form wrap1200">
   <h1>车站查询：</h1>
   <div class="errMsg"><?php if($data['errMsg']!='Y') echo $data['errMsg']; ?></div>
   <div class="search">
    <form id="postForm" name="postForm" method="get" action="station.php">
    车站：<input type="text" id="station_name" name="station_name" value="<?php echo $_GET['station_name']; ?>" class="station"/>
    <input type="submit" value="搜索"/>
    </form>
  </div>
  <?php if (is_array($data['datas'])):?>
  <table width="100%" height="0" border="0" cellpadding="0" cellspacing="0" style="margin-top:10px;">
       <tr>
	      <th>车次</th>
		  <th>始发站<br/>终到站</th>
		  <th>始发时间<br/>终到时间</th>
          <th>历时</th>
          <th>价格</th>
	   </tr>
       <?php foreach ($data['datas'] as $key=>$val): ?>
       <tr>
         <td><strong><?php echo $val['train_code']; ?></strong><br/><?php echo $val['train_class']; ?></td>
         <td><?php echo $val['start_station'].'<Br/>'.$val['end_station']; ?></td>
         <td><?php echo $val['start_time'].'<Br/>'.$val['arrive_time']; ?></td>
         <td><?php echo $val['cost_time']; ?></td>
         <td>
           <?php foreach ($val['pr_info'] as $yp): ?>
           <?php echo $yp['na'].' ￥'.$yp['pr'].'元';?><br/>
           <?php endforeach; ?>
         </td>
       </tr>
       <?php endforeach; ?>
  </table>
  <? else: ?>
  <?php endif; ?>
</div>

<script type="text/javascript">
 $('#train_date').fdatepicker({format: 'yyyy-mm-dd'});
 $(function(){
	 
  $("#station_name").autocomplete(station_array, {
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
		   train_station: {required: true},
		   train_date: {required: true}
        }
  });
});
</script>
<?php require 'bottom.php';?>