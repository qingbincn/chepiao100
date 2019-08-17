<?php require 'header.php';?>
<?php require 'session.php';?>
<?php
$train = new TrainApi();
$train->method('train.passenger.lists');
$userInfo = $train->action(array('token'=>$_SESSION['token']));
$userData = $userInfo['datas'];
?>
<div class="form wrap1200">
    <h1>乘客管理: <a href="passenger-add.php">+添加乘客</a></h1>
    <?php if ($userInfo['errMsg'] == 'Y') : ?>
    <table width="100%" height="0" border="0" cellpadding="0" cellspacing="0" style="margin-top:10px;">
       <tr>
	      <th>姓名</th>
		    <th>性别</th>
            <th>国家/地区</th>
		    <th>证件类型</th>
		    <th>证件号码</th>
		    <th>旅客类型</th>
		    <th>状态</th>
		    <th width="80px">操作</th>
	   </tr
      ><tbody>
      <?php foreach ($userData as $val): ?>
        <tr>
          <td><?php echo $val['passenger_name']; ?></td>
          <td><?php echo $val['sex_name']; ?></td>
          <td><?php echo $val['country_code']; ?></td>
          <td><?php echo $val['passenger_id_type_name']; ?></td>
          <td><?php echo $val['passenger_id_no']; ?></td>
          <td><?php echo $val['passenger_type_name']; ?></td>
          <td><?php echo $val['passenger_status']; ?></td>
          <td>
           <a href="passenger-edit.php?<?php echo 'passenger_name='.$val['passenger_name'].'&sex_code='.$val['sex_code'].'&passenger_id_no='.$val['passenger_id_no'].'&passenger_type='.$val['passenger_type'].'&passenger_id_type_code='.$val['passenger_id_type_code'].'&allEncStr='.$val['allEncStr']; ?>">编辑</a>           &nbsp;
           <a href="passenger-delete.php?<?php echo 'passenger_name='.$val['passenger_name'].'&passenger_id_no='.$val['passenger_id_no'].'&isUserSelf='.$val['isUserSelf'].'&passenger_id_type_code='.$val['passenger_id_type_code'].'&allEncStr='.$val['allEncStr']; ?>" onClick="return doDelete();">删除</a>
           </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
    <?php else: ?>
    <div class="errMsg"><?php echo $userInfo['errMsg']; ?></div>
    <?php endif; ?>
</div>
<script>
var doDelete = function()
{
  if (confirm('您确定要删除选中的常用乘客吗?') == false){
    return false;
  }
}

</script>
<?php require 'bottom.php';?>