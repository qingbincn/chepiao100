<?php require 'header.php';?>
<?php

$param = array();
$param['token'] = $_SESSION['token'];
$param['sequence_no'] = $_GET['sequence_no'];

$train = new TrainApi();
$train->method('train.pay.gateway');
$data = $train->action($param);

if ( $data['errMsg'] != 'Y'){
  echo '<script>alert("'.$data['errMsg'].'");location.href = "order-no.php"; </script>';
  exit();
}

$data = $data['datas'];
?>
<script type="text/javascript" src="plugins/trainCaptcha/js/trainCaptcha.js"></script>
<link type="text/css" href="plugins/trainCaptcha/css/trainCaptcha.css" rel="stylesheet"/>
<div class="form wrap1200">
    <h1>支付方式：</h1>
    <form id="postForm" name="postForm" method="post" action="pay-epay.php">
    <input type="hidden" name="pay_token" value="<?php echo $data['pay_token']; ?>" />
    <div class="paymentBanks">
      <ul>
        <?php foreach ($data['banks'] as $bankId=>$bankName): ?>
        <li>
          <label class="bank_<?php echo $bankId; ?>" title="<?php echo $bankName; ?>">
              <?php echo $bankName; ?>
              <input type="radio" name="bankId" id="bankId" value="<?php echo $bankId; ?>"  onClick="doPayment()"/>
          </label>
        </li>
        <?php endforeach; ?>
      </ul>
      <div style="clear:both"></div>
    </div>
    </form>
  </div>
</div>
<script type="text/javascript">
$(function(){
   $('#TrainCaptcha').TrainCaptcha({input:"randCode",imgUrl:"captcha.php?module=login"});
   
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
           bankId: {required: true}
        }
  });
});

// 表单提交
var doPayment = function()
{
  $('#postForm').submit();
}

</script>
<?php require 'bottom.php';?>