<?php require 'header.php';?>
<?php
if ($_POST['action'] == 'query') {
  $param = $_POST;  
  $train = new TrainApi();
  $train->method('train.ticket.dynamic');
  $data = $train->action($param);
}
?>
<link type="text/css" rel="stylesheet" href="plugins/complete/css/jquery.autocomplete.css" />
<script type="text/javascript" src="plugins/complete/js/code_name.js"></script>
<script type="text/javascript" src="plugins/complete/js/station_name.js"></script>
<script type="text/javascript" src="plugins/complete/js/jquery.autocomplete.js"></script>
<div class="form wrap1200">
   <div class="login">
    <h1>正晚点查询：</h1>
	<div class="errMsg"><?php if($data['errMsg']!='Y') echo $data['errMsg']; ?></div>
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
      <form id="postForm" name="postForm" method="post" action="dynamic.php">
      <input type="hidden" name="action" value="query" />
      <tbody>
        <tr>
          <td width="15%">查询类型：</td>
          <td  width="85%">
             <select name="station_type" id="station_type">
                <option value="0" <?php if($_POST['station_type']=="0") echo 'selected';?>>到达站 </option>
                <option value="1" <?php if($_POST['station_type']=="1") echo 'selected';?>>出发站</option>
             </select>
             <span class="vaildTip"></span>
          </td>
        </tr>
        <tr>
          <td>车次：</td>
          <td>
              <input type="text" id="train_code" name="train_code" value="<?php echo $_POST['train_code']; ?>">
              <span class="vaildTip"></span>
          </td>
        </tr>
        <tr>
          <td>车站：</td>
          <td>
              <input type="text" id="station_name" name="station_name" value="<?php echo $_POST['station_name']; ?>">
              <span class="vaildTip"></span>
          </td>
        </tr>
        </tr>
        <tr>
          <td></td>
          <td><input type="submit" value="查询"/></td>
        </tr>
      </tbody>
      </form>
    </table>
    <?php if ($data['datas']): ?>
    <div class="dynamic"><?php echo $data['datas']['message']; ?></div>
    <div class="attention"><?php echo $data['datas']['attention']; ?></div>
    <?php endif; ?>
  </div>
</div>
<script type="text/javascript">
$(function(){
   
   $("#postForm").validate({ 
	    errorElement: "em",
	    errorPlacement: function(error, element) {
			var this_id = element.attr('id');
	        element.parent("td").find(".vaildTip").html('');
	        error.appendTo( element.parent("td").find(".vaildTip") );
	    },
	    success: function(label) {
	        label.text("通过信息验证！").addClass("success");
	    },
        rules: {
           station_type: {required: true},
		   train_code: {required: true},
		   station_name: {required: true}
        }
  });
  
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
  
});
</script>
<?php require 'bottom.php';?>