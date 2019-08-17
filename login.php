<?php require 'header.php';?>
<?php
if ($_POST['action'] == 'login') {
  $param = $_POST;
  $param['token']  = $_SESSION['token'];
  $param['answer'] = $_POST['answer'];
  
  $train = new TrainApi();
  $train->method('train.user.login');
  $data = $train->action($param);
  
  if ($data['errMsg'] == 'Y') {
      $_SESSION['username'] = $data['datas']['user_name'];
      header('location:ticket.php');
      exit();
  } else {
    $errMsg = $data['errMsg'];
  }
}

$train = new TrainApi();
$train->method('train.user.token');
$data = $train->action();
$_SESSION['token'] = $data['datas']['token'];
?>
<script type="text/javascript" src="plugins/trainCaptcha/js/trainCaptcha.js"></script>
<link type="text/css" href="plugins/trainCaptcha/css/trainCaptcha.css" rel="stylesheet"/>
<div class="form wrap1200">
   <div class="login">
    <h1>登录：</h1>
    <?php if ($errMsg): ?>
    <div class="errMsg"><?php echo $errMsg; ?></div>
    <?php endif; ?>
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
      <form id="postForm" name="postForm" method="post" action="login.php">
      <input type="hidden" name="action" value="login" />
      <tbody>
        <tr>
          <td width="15%">登录名：</td>
          <td width="85%">
            <input type="text" id="username" name="username" value=""/>
            <span class="vaildTip"></span>
          </td>
        </tr>
        <tr>
          <td>密码：</td>
          <td>
            <input type="password" id="password" name="password" value=""/>
            <span class="vaildTip"></span>
          </td>
        </tr>
        <tr>
          <td valign="top">验证码：</td>
          <td class="captcha">
            <input type="hidden" id="answer" name="answer" value=""/>
            <div id="TrainCaptcha" class="TrainCaptcha"></div>
          </td>
        </tr>
        <tr>
          <td></td>
          <td><input type="button" value="登录" onClick="doSubForm()"/></td>
        </tr>
      </tbody>
      </form>
    </table>
  </div>
</div>
<script type="text/javascript">
$(function(){
   $('#TrainCaptcha').TrainCaptcha({input:"answer",imgUrl:"captcha.php?module=login",damaUrl:"captcha.php?action=dama"});
   
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
           username: {required: true},
		   password: {required: true},
		   answer: {required: true}
        }
  });
});

// 表单提交
var doSubForm = function()
{
    var answer = $('#answer').val();
    if (answer == '') return;
  
    $.post('checkcode.php?module=login', {answer:answer}, function(data){
        data = JSON.parse(data);
		if (data.errMsg == 'Y') {
	        $('#answer').data('flag', 'success').click();
			$('#postForm').submit();
		} else {
		    $('#answer').data('flag', 'failure').click();
	    }
    });
}

</script>
<?php require 'bottom.php';?>