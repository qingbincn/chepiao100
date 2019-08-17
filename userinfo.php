<?php require 'header.php';?>
<?php require 'session.php';?>
<?php
$train = new TrainApi();
$train->method('Train/Passengers/userInfo');
$userInfo = $train->action(array('session'=>$_SESSION['session']));
$userData = $userInfo['data'];
?>
<div class="form wrap1200">
    <h1>用户信息：</h1>
    <?php if ($userInfo['errMsg'] == 'Y') : ?>
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tbody>
        <tr>
          <td width="15%">姓名：</td>
          <td width="85%"><?php echo $userData['name']; ?></td>
        </tr>
        <tr>
          <td>性别：</td>
          <td><?php echo $userData['sex_name']; ?></td>
        </tr> 
        <tr>
          <td>证件类型：</td>
          <td><?php echo $userData['id_name']; ?></td>
        </tr>
        <tr>
          <td>证件号码：</td>
          <td><?php echo $userData['id_no']; ?></td>
        </tr>
        <tr>
          <td>旅客类型：</td>
          <td><?php echo $userData['type_name']; ?></td>
        </tr>
      </tbody>
    </table>
    <?php else: ?>
    <div class="errMsg"><?php echo $userInfo['errMsg']; ?></div>
    <?php endif; ?>
</div>
<?php require 'bottom.php';?>