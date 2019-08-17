<?php require 'header.php';?>
<?php require 'session.php';?>
<?php
if ($_POST['action'] == 'edit') {
  $param = $_POST;
  $param['token']  = $_SESSION['token'];
  $train = new TrainApi();
  $train->method('train.passenger.edit');
  $data = $train->action($param);
  if ($data['errMsg'] == 'Y') {
    echo '<script>alert("乘客编辑成功！");location.href = "passenger.php"; </script>';
  } else {
    echo '<script>alert("'.$data['errMsg'].'");history.back(); </script>';
  }
  exit();
}
?>
<div class="form wrap1200">
   <div class="login">
    <h1>编辑乘客：</h1>
    <?php if ($errMsg): ?>
    <div class="errMsg"><?php echo $errMsg; ?></div>
    <?php endif; ?>
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
      <form id="postForm" name="postForm" method="post" action="passenger-edit.php">
      <input type="hidden" name="action" value="edit" />
      <tbody>
        <tr>
          <td width="15%">姓名：</td>
          <td width="85%">
              <input type="text" id="passenger_name" name="passenger_name" value="<?php echo $_GET['passenger_name'];?>" />
              <input type="hidden" id="old_passenger_name" name="old_passenger_name" value="<?php echo $_GET['passenger_name'];?>">
              <span class="vaildTip"></span>
          </td>
        </tr>
        <tr>
          <td>性别：</td>
          <td>
             <select name="sex_code" id="sex_code">
                <option value="M" <?php if($_GET['sex_code']=="M") echo 'selected';?>>男</option>
                <option value="F" <?php if($_GET['sex_code']=="F") echo 'selected';?>>女</option>
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
                <option value="1" <?php if($_GET['passenger_id_type_code']=="1") echo 'selected';?>>二代身份证</option>
                <option value="C" <?php if($_GET['passenger_id_type_code']=="C") echo 'selected';?>>港澳通行证</option>
                <option value="G" <?php if($_GET['passenger_id_type_code']=="G") echo 'selected';?>>台湾通行证</option>
                <option value="B" <?php if($_GET['passenger_id_type_code']=="B") echo 'selected';?>>护照</option>
             </select>
             <input type="hidden" id="old_passenger_id_type_code" name="old_passenger_id_type_code" value="<?php echo $_GET['passenger_id_type_code'];?>">
             <span class="vaildTip"></span>
          </td>
        </tr>
        <tr>
          <td>证件号码：</td>
          <td>
              <input type="text" id="passenger_id_no" name="passenger_id_no" value="<?php echo $_GET['passenger_id_no'];?>">
              <input type="hidden" id="old_passenger_id_no" name="old_passenger_id_no" value="<?php echo $_GET['passenger_id_no'];?>">
              <span class="vaildTip"></span>
          </td>
        </tr>
        <tr>
          <td>旅客类型：</td>
          <td>
          <select name="passenger_type" id="passenger_type">
                <option value="1" <?php if($_GET['passenger_type']=="1") echo 'selected';?>>成人</option>
                <option value="2" <?php if($_GET['passenger_type']=="2") echo 'selected';?>>儿童</option>
                <option value="3" <?php if($_GET['passenger_type']=="3") echo 'selected';?>>学生</option>
                <option value="4" <?php if($_GET['passenger_type']=="4") echo 'selected';?>>军疾</option>
             </select>
             <span class="vaildTip"></span>
          </td>
        </tr>
        <tr>
          <td>
          	<input type="hidden" name="allEncStr" id="allEncStr" value="<?php echo $_GET['allEncStr']; ?>" />
          </td>
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