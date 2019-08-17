<?php require 'header.php';?>
<?php require 'session.php';?>
<?php
if ($_POST['action'] == 'add') {
  $param = $_POST;
  $param['token']  = $_SESSION['token'];
  
  $train = new TrainApi();
  $train->method('train.passenger.add');
  $data = $train->action($param);
  
  if ($data['errMsg'] == 'Y') {
    echo '<script>alert("联系人添加成功！");location.href = "passenger.php"; </script>';
  } else {
    echo '<script>alert("'.$data['errMsg'].'");location.href = "passenger-add.php"; </script>';
  }
  exit();
}
?>
<div class="form wrap1200">
   <div class="login">
    <h1>添加联系人：</h1>
    <?php if ($errMsg): ?>
    <div class="errMsg"><?php echo $errMsg; ?></div>
    <?php endif; ?>
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
      <form id="postForm" name="postForm" method="post" action="passenger-add.php">
      <input type="hidden" name="action" value="add" />
      <tbody>
        <tr>
          <td width="15%">姓名：</td>
          <td width="85%">
              <input type="text" id="passenger_name" name="passenger_name" value="">
              <span class="vaildTip"></span>
          </td>
        </tr>
        <tr>
          <td>性别：</td>
          <td>
             <select name="sex_code" id="sex_code">
                <option value="M">男</option>
                <option value="F">女</option>
             </select>
             <span class="vaildTip"></span>
          </td>
        </tr>
        <tr>
          <td>国家地址：</td>
          <td>
             <select name="country_code" id="country_code">
                 <option value="CN">中国</option>
             </select>
          </td>
        </tr> 
        <tr>
          <td>证件类型：</td>
          <td>
             <select name="passenger_id_type_code" id="passenger_id_type_code">
                <option value="1">二代身份证</option>
                <option value="C">港澳通行证</option>
                <option value="G">台湾通行证</option>
                <option value="B">护照</option>
             </select>
             <span class="vaildTip"></span>
          </td>
        </tr>
        <tr>
          <td>证件号码：</td>
          <td>
              <input type="text" id="passenger_id_no" name="passenger_id_no" value="">
              <span class="vaildTip"></span>
          </td>
        </tr>
        <tr>
          <td>旅客类型：</td>
          <td>
          <select name="passenger_type" id="passenger_type">
                <option value="1">成人</option>
                <option value="2">儿童</option>
                <option value="3">学生</option>
                <option value="4">军疾</option>
             </select>
             <span class="vaildTip"></span>
          </td>
        </tr>
        <tr>
          <td></td>
          <td><input type="submit" value="保存"/></td>
        </tr>
      </tbody>
      </form>
    </table>
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
           name: {required: true},
		   sex_code: {required: true},
		   id_type: {required: true},
		   id_no: {required: true},
		   type: {required: true}
        }
  });
});
</script>
<?php require 'bottom.php';?>