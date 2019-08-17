<?php require 'header.php';?>
<?php require 'session.php';?>
<?php
  // 乘客信息
  $train = new TrainApi();
  $train->method('train.passenger.dtos');
  $data = $train->action(array('token'=>$_SESSION['token']));
  $passenger = $data['datas'];
  // 车次信息
  $param = $_POST;
  $param['token'] = $_SESSION['token'];
  $train = new TrainApi();
  $train->method('train.order.submit');
  $data = $train->action($param);
  
  if ($data['errMsg'] != 'Y'){
      echo '<script>alert("'.$data['errMsg'].'");history.back(); </script>';
	  exit();
  }

  // 拆分数据  
  $train  = $data['datas'];
  $ypInfo = $train['yp_info'];
  $ticket_token= $train['ticket_token'];
?>
<script type="text/javascript" src="plugins/trainCaptcha/js/trainCaptcha.js"></script>
<link type="text/css" href="plugins/trainCaptcha/css/trainCaptcha.css" rel="stylesheet"/>


<script type="text/javascript" src="plugins/confirm/js/dialog.js"></script>
<link rel="stylesheet" href="plugins/confirm/css/dialog.css" />


<div class="form wrap1200">
   <div class="trainInfo">
   <h1>车次信息：</h1>
    <div class="info">
    <?php echo $train['train_date']; ?>（<?php echo $train['train_week']; ?>）<?php echo $train['train_code']; ?><span>次</span>
    <?php echo $train['from_station']; ?><span>站</span>（<?php echo $train['start_time']; ?>开）—<?php echo $train['to_station']; ?><span>站（<?php echo $train['arrive_time']; ?>到）</span>
    </div>
    <div class="yp">
    <?php foreach ($ypInfo as $yp): ?>
      <?php echo $yp['na'].'(<span class="has">￥'.$yp['pr'].'元</span>)'.$yp['yp'];?>　
    <?php endforeach; ?>
    </div>
  </div>
  <div class="passengerInfo">
    <h1>乘客信息</h1>
    <ul>
        <?php foreach ($passenger as $pass): ?>
        <label id="pass_<?php echo $pass['passenger_id_no']; ?>">
            <input type="checkbox" value="<?php echo $pass['passenger_name'].'_'.$pass['passenger_id_type_code'].'_'.$pass['passenger_id_type_name'].'_'.$pass['passenger_id_no'].'_'.$pass['allEncStr']; ?>" name="passenger" onClick="doPassengerAdd(this)"/>
			<?php echo $pass['passenger_name']; ?>
        </label>
        <?php endforeach; ?>
    </ul>
  </div>
  <form id="postForm" name="postForm" method="post" action="">
  <input type="hidden" name="choose_seat" value=""/>
  <div class="ypinfo">
    <table width="100%" height="0" border="0" cellpadding="0" cellspacing="0">
      <tr>
         <th>席别 </th>
         <th>票种</th>
         <th>姓名</th>
         <th>证件类型</th>
         <th>证件号码</th>
         <th width="40px">操作</th>
      </tr>
    </table>
    <div class="add">
      <a href="javascript:doPassengerAjax()" target="_blank">+新增乘客</a>
    </div>
  </div>
  <div class="captchaInfo">
      <input type="hidden" id="answer" name="answer" value=""/>
      <input type="button" value="提交订单" onClick="doSubForm()"/>
  </div>
  <input type="hidden" name="ticket_token" id="ticket_token" value="<?php echo $ticket_token; ?>" />
  </form>
  <div class="dialog-box" style="display:none;"></div>
  <div class="ajax-box" style="display:none;"></div>
</div>


<div class="ypinfo_html" style="display:none;">
<table>
  <tr id="<passenger_id_no>">
  <td>
     <select name="seat_type[]">
         <?php foreach ($ypInfo as $yp): ?>
         <option value="<?php echo $yp['id']?>"><?php echo $yp['na'].'('.$yp['pr'].'元)';?></option>
         <?php endforeach; ?>
     </select>
  </td>
  <td>
      <select name="ticket_type[]">
          <option value="1" <?php if($_GET['ticket_type']=="1") echo 'selected';?>>成人</option>
          <option value="2" <?php if($_GET['ticket_type']=="2") echo 'selected';?>>儿童</option>
          <option value="3" <?php if($_GET['ticket_type']=="3") echo 'selected';?>>学生</option>
          <option value="4" <?php if($_GET['ticket_type']=="4") echo 'selected';?>>军疾</option>
       </select>
  </td>
  <td>
      <span><passenger_name></span>
      <input type="hidden" name="passenger_name[]" value="<passenger_name>" />
  </td>
  <td>
     <span><passenger_id_type_name></span>
     <input type="hidden" name="passenger_id_type_name[]" value="<passenger_id_type_name>" />
     <input type="hidden" name="passenger_id_type_code[]" value="<passenger_id_type_code>" />
  </td>
  <td>
     <span><passenger_id_no></span>
     <input type="hidden" name="passenger_id_no[]" value="<passenger_id_no>" />
     <input type="hidden" name="allEncStr[]" value="<allEncStr>" />
  </td>
  <td onClick="doPassengerDel(this)" class="button">删除</td>
  </tr>
  </table>
</div>


<script type="text/javascript">

var ypinfo_html    = '';
var doShowRancode  = 'Y';
var doShowChoose   = 'N';
$(function(){
	ypinfo_html = $('.ypinfo_html table').html();
});

// 删除
var doPassengerDel = function(obj) {
	var  passenger_id_no = $(obj).parents('tr').attr('id').replace('yp_');
    $(obj).parents('tr').remove();
	$('#'+passenger_id_no).find('input').prop('checked', false);
}

// 选择
var doPassengerAdd = function(obj) {
	 
	 if ($('.ypinfo tr').size()==6) {
		 alert('最多只能购买5张车票!');
		 $(obj).prop('checked', false);
	     return;
	 }
	 var p = $(obj).val().split('_');
	 var passenger_name     = p[0];
	 var passenger_id_type_code  = p[1];
	 var passenger_id_type_name  = p[2];
	 var passenger_id_no    = p[3];
	 var allEncStr = p[4];
	 var html  = ypinfo_html;
	
    if ($(obj).prop('checked') == true) {
		html = html.replace(/<passenger_name>/g, passenger_name);
		html = html.replace(/<passenger_id_type_code>/g, passenger_id_type_code);
		html = html.replace(/<passenger_id_type_name>/g, passenger_id_type_name);
		html = html.replace(/<passenger_id_no>/g, passenger_id_no);
		html = html.replace(/<allEncStr>/g, allEncStr);
		$('.ypinfo>table').append(html);
    } else {
	    $('#yp_'+passenger_id_no).remove();
	}
}

// 表单提交
var doSubForm = function() {
	
    if ($('.ypinfo tr').size()==1) {
		 alert('请选择购票乘客!');
	     return;
	 }
	doPassengerOrder();

}

//  验证码管理
var doCaptchaCode = function() {
	
    var answer = $('#answer').val();
    if (answer == '') return;
  
    $.post('checkcode.php?module=passenger', {answer:answer}, function(data){
        data = JSON.parse(data);
		if (data.errMsg == 'Y') {
	        $('#answer').data('flag', 'success').click();
			 doPassengerConfirm();
		} else {
		    $('#answer').data('flag', 'failure').click();
	    }
    });

}

// 订单确认提交
var doPassgengerDialog = function(json)
{
	var html = '<div class="dailogQueue" id="dailogQueue">本次列车剩余：<span>'+json.ticket+'张</span> ';
	html += '队列：<span>'+json.count+'</span><br/>';
	html += '系统将随机为您申请席位，暂不支持自选席位。';
	html += '<br></br>';
	html += '<div id="TrainChoose" class="TrainChoose"></div>';
	html += '<br><br>';
    html += '<div id="TrainCaptcha" class="TrainCaptcha"></div>';
	html += '</div>';

	var $d = $(".dialog-box");
	$d.dialog({
		title: '请核对以下信息',	
		dragable:true,
		html: '', 						
		width: 450,					
		height: doShowRancode == 'Y' ? 510 : 280,				
		cannelText: '取消',		
		confirmText: '确认',	
		showFooter:true,
		onClose: function(){
		  $('#answer').data('flag', 'init').click();
		},
		onOpen: false,		
		onConfirm: function() {
			if (doShowRancode == 'Y') {
			    doCaptchaCode();
			} else {
		        doPassengerConfirm();   
			}	 
		},
		onCannel: function(){  	
			$('#answer').data('flag', 'initial').click();
		},
		getContent:function(){
			$d.find('.body-content').html(html);
			if (doShowRancode == 'Y') {
			   $('#TrainCaptcha').TrainCaptcha({input:"answer",imgUrl:"captcha.php?module=passenger",damaUrl:"captcha.php?action=dama"});
			} else {
			   $('#TrainCaptcha').remove();
			}
		}
	}).open(); 
}

// 出现错误
var doPassengerFailure = function()
{
   $(".dialog-box").dialog().close();
   $('#answer').data('flag', 'failure').click();
}

// 订单校验
var doPassengerOrder = function() {
    var param = $("#postForm").serialize();
	$.post('order-submit.php?action=checkOrder', param, function(data){
        data = JSON.parse(data);
		if (data.errMsg == 'Y') {
			doShowRancode = data.datas.is_captcha;
			doShowChoose  = data.datas.is_choose;
	        doPassengerQueue();
		} else {
		   alert(data.errMsg); 
		   history.back();
		}
	});
}

// 队列:只在单一订单时使用
var doPassengerQueue = function(seat) {
	var param = $("#postForm").serialize();
	$.post('order-submit.php?action=queueCount', param, function(data){
	   data = JSON.parse(data);
	    if (data.errMsg == 'Y') {
	        doPassgengerDialog(data.datas);
			if (doShowChoose == 'Y') doTrainChoose();
		} else {
		   alert(data.errMsg); 
		   doPassengerFailure();
		}
	});
}

// 提交订单
var doPassengerConfirm = function() {
	getTrainChoose();
    var param = $("#postForm").serialize();
	$.post('order-submit.php?action=confirm', param, function(data){
		data = JSON.parse(data);
	    if (data.errMsg == 'Y') {
	         var html = '<div class="loading"></div>';
			 html += '<div class="content">';
			 html += '订单已经提交，系统正在处理中，请稍等。<br/>';
			 html += '查看订单处理情况，请点<a href="order-no.php">"未支付订单</a>"';
			 html += '</div>';
	         $('#dailogQueue').html(html);
			 $(".dialog-box").find('.footer').remove();
			 $(".dialog-box").find('.body').css('height', 150);
	         doPassengerWaitTime();
		} else {
		   alert(data.errMsg); 
		   doPassengerFailure();
		}
	});
}

// 订单等待
var doPassengerWaitTime = function() {
	var param = $("#postForm").serialize();
	$.post('order-submit.php?action=waitTime', param, function(data){
		data = JSON.parse(data);
	    if (data.errMsg == 'Y') {
			if (typeof(data.datas.sequence_no) == 'string' && data.datas.sequence_no!=""){
			    doPassengerReturn(data.datas.sequence_no);
			} else {
				setTimeout("doPassengerWaitTime()",2000)
			}
		} else {
		   alert(data.errMsg); 
		   doPassengerFailure();
		}
	});
}

// 进入队列
var doPassengerReturn = function(sequence_no) {
    var ticket_token = $('#ticket_token').val();
	$.post('order-submit.php?action=returnQueue', {ticket_token:ticket_token,sequence_no:sequence_no}, function(data){
	   data = JSON.parse(data);
	   if (data.errMsg == 'Y') {
           location.href = "order-no.php";
	   }
	});
}

// 添加用户
var doPassengerAjax  = function() {
    var html = '<iframe src="passenger-ajax.php" style="width:'+500+';height:'+500+'" frameborder=0 marginheight=0 marginwidth=0 scrolling=no></iframe>';
  	var $d = $(".ajax-box");
	$d.dialog({
		title: '添加乘客',	
		dragable:true,
		html: '', 						
		width: 550,					
		height: 510,				
		showFooter:false,
		getContent:function(){
			$d.find('.body-content').html(html);
		}
	}).open();
}

// 获取座位
var getTrainChoose = function()
{
	var choose = [];
	var size = $('#TrainChoose').find('div').size();
	for (var i = 0; i<size; i++) {
	   var value = $('input[name=choose_'+i+']:checked').val(); 
	   choose.push(value);
	}
	$('#postForm').find('input[name=choose_seat]').val(choose.join(','));
}
// 生成座位
var doTrainChoose = function()
{
   $('#TrainChoose').html('');
   $('#postForm').find('select[name*=seat_type]').each(function(i, v) {
     $('#TrainChoose').append('<div rel="'+i+'">'+doMakeChoose($(this).val(), i)+'</div>');
   });
}
var doMakeChoose = function(seat, index)
{
   var html = '';
   if (seat == '9') {
	  html += '<label>窗 | </label>';
      html += '<label><input name="choose_'+index+'" type="radio" value="A" checked/>A</label>';
	  html += '<label><input name="choose_'+index+'" type="radio" value="C" />C</label>';
	  html += '<label> | 过道 | </label>';
	  html += '<label><input name="choose_'+index+'" type="radio" value="F" />F</label>';
	  html += '<label> | 窗</label>';
   }
   if (seat == 'M') {
	  html += '<label>窗 | </label>';
      html += '<label><input name="choose_'+index+'" type="radio" value="A" checked/>A</label>';
	  html += '<label><input name="choose_'+index+'" type="radio" value="C" />C</label>';
	  html += '<label> | 过道 | </label>';
	  html += '<label><input name="choose_'+index+'" type="radio" value="D" />D</label>';
	  html += '<label><input name="choose_'+index+'" type="radio" value="F" />F</label>';
	  html += '<label> | 窗</label>';
   }
   if (seat == 'O') {
	  html += '<label>窗 | </label>';
      html += '<label><input name="choose_'+index+'" type="radio" value="A" checked/>A</label>';
	  html += '<label><input name="choose_'+index+'" type="radio" value="B" />B</label>';
	  html += '<label><input name="choose_'+index+'" type="radio" value="C" />C</label>';
	  html += '<label> | 过道 | </label>';
	  html += '<label><input name="choose_'+index+'" type="radio" value="D" />D</label>';
	  html += '<label><input name="choose_'+index+'" type="radio" value="F" />F</label>';
	  html += '<label> | 窗</label>';
   }
   return html;
}
</script>
<?php require 'bottom.php';?>