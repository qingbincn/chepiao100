<?php require 'header.php';?>
<?php
if (!empty($_GET['train_code'])){
    $train_code = $_GET['train_code'];
    $train_code = explode("/", $train_code);
    $train_code = $train_code[0];
  $train = new TrainApi();
  $train->method('train.ticket.code');
  $data = $train->action(['train_code' => $train_code]);
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
   <h1>车次查询：</h1>
   <div class="errMsg"><?php if($data['errMsg']!='Y') echo $data['errMsg']; ?></div>
   <div class="search">
    <form id="postForm" name="postForm" method="get" action="code.php">
    车次：<input type="text" id="train_code" name="train_code" value="<?php echo $_GET['train_code']; ?>" class="station"/>
    <input type="submit" value="搜索"/>
    </form>
  </div>
  <?php if (is_array($data['datas'])):?>
  
    <table width="100%" height="0" border="0" cellpadding="0" cellspacing="0" style="margin-top:10px;">
       <tr>
	      <th>车次</th>
		  <th>始发站<br/>终到站</th>
		  <th>始发时间<br/>终到时间</th>
          <th>历时<br />里程</th>
          <th>价格</th>
	   </tr>
       <tr>
         <td><strong><?php echo $data['datas']['summ']['train_code']; ?></strong></td>
         <td><?php echo $data['datas']['summ']['start_station'].'<BR/>'.$data['datas']['info']['end_station']; ?></td>
         <td><?php echo $data['datas']['summ']['start_time'].'<BR/>'.$data['datas']['info']['arrive_time']; ?></td>
         <td><?php echo $data['datas']['summ']['cost_time'].'<BR/>'.$data['datas']['info']['mileage'].'KM'; ?></td>
         <td>
           <?php foreach ($data['datas']['summ']['pr_info'] as $yp): ?>
           <?php echo $yp['na'].' ￥'.$yp['pr'].'元';?><br/>
           <?php endforeach; ?>
         </td>
       </tr>
  </table>
  
  
  <table width="100%" height="0" border="0" cellpadding="0" cellspacing="0">
    <tr>
    <td class="head">&nbsp;</td>
    <?php if (isset($data['datas']['head'])) :?>
    <?php foreach($data['datas']['head'] as $key=>$val): ?>
    <td class="head"><?php echo $val; ?></td>
    <?php endforeach; ?>
    </tr>
    <?php foreach($data['datas']['item'] as $item): ?>
    <tr class="item">
    <td>&nbsp;</td>
    <?php foreach($item as $key=>$val): ?>
    <td><?php echo $val; ?></td>
    <?php endforeach; ?>
    </tr>
    <?php endforeach; ?>
    <?php endif; ?>
    </table>
  <? else: ?>
  <?php endif; ?>
</div>

<script type="text/javascript">
 $('#train_date').fdatepicker({format: 'yyyy-mm-dd'});
 $(function(){
	 
 $("#train_code").autocomplete(code_array, {
		minChars: 0,
		width: 200,
		max: 10,
		scrollHeight: 300,
		matchContains: true,
		autoFill: false,
		formatItem: function(data) 
		{
			return data[0];
		},
		formatMatch: function(data) 
		{
		    return data[0];
		},
		formatResult: function(data) 
		{
			return data[0];
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
		   train_code: {required: true},
		   train_date: {required: true}
        }
  });
});
</script>
<?php require 'bottom.php';?>