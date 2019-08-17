<?php require 'header.php';?>
<?php
$train = new TrainApi();
$train->method('train.agency.province');
$province = $train->action();
$province = $province['datas'];


if (!empty($_GET['from_station'])&&!empty($_GET['to_station'])&&!empty($_GET['train_date'])){
  $param = $_GET;
  $train = new TrainApi();
  $train->method('train.agency.query');
  $data = $train->action($param);
}

?>
<div class="form wrap1200">
   <h1>代售点查询：</h1>
   <div class="errMsg"><?php if($data['errMsg']!='Y') echo $data['errMsg']; ?></div>
   <div class="search">
    <form id="postForm" name="postForm" method="get" action="ticket.php">
    省份：<select name="province" id="province" onChange="doCity(this.value)">
<option value="" selected="selected">请选择省</option>
<?php foreach ($province as $name): ?>
<option value="<?php echo $name; ?>"><?php echo $name; ?></option>
<?php endforeach; ?>
</select>
    城市：<select id="city" name="city" onChange="doCounty(this.value)">
             <option value="">选择城市</option>
         </select>
    县区：<select id="county" name="county">
             <option value="">选择县区</option>
         </select>
    <input type="button" value="搜索" onClick="doQuery()"/>
    </form>
  </div>
</div>

<script type="text/javascript">
// 加载城市
var doCity = function(province) {
	if (province == '') {
	   $('#city').html('<option value="">选择城市</option>');
	   $('#county').html('<option value="">选择县区</option>');
	   return;
	}
	
	$.post('agency-action.php?action=city',{province:province}, function(html){
	    $('#city').html(html);
	});
}
// 加载县区
var doCounty = function(city) {
    var province = $('#province').val();
	$.post('agency-action.php?action=county',{province:province, city:city}, function(html){
	    $('#county').html(html);
	});
}
// 代售点查询
var doQuery = function() {
	$('.form').find('table').remove();
    var province = $('#province').val();
	var city     = $('#city').val();
	var county   = $('#county').val();
	if (province == '') {
		$('#province').focus();
		alert('请选择省份!');
		return;
	}
	if (city == '') {
		$('#city').focus();
		alert('请选择城市!');
		return;
	}
	$.post('agency-action.php?action=query',{province:province, city:city, county:county}, function(html){
	    $('.form').append(html);
	});
}
</script>
<?php require 'bottom.php';?>