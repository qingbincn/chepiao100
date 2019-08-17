<?php require 'header.php';?>
<?php require 'session.php';?>
<?php
$train = new TrainApi();
$train->method('Train/Passengers/userInfo');
$userInfo = $train->action(array('token'=>$_SESSION['token']));
$userData = $userInfo['datas'];
?>
<div class="form wrap1200">
    <h1>用户信息：</h1>
    <?php if ($userInfo['errMsg'] == 'Y') : ?>
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tbody>
        <tr>
          <td width="15%">姓名：</td>
          <td width="85%"><?php echo $userData['user_name']; ?></td>
        </tr>
        <tr>
          <td>邮件：</td>
          <td><?php echo $userData['email']; ?></td>
        </tr> 
        <tr>
          <td>欢迎：</td>
          <td><?php echo $userData['user_regard']; ?></td>
        </tr>
      </tbody>
    </table>
    <?php else: ?>
    <div class="errMsg"><?php echo $userInfo['errMsg']; ?></div>
    <?php endif; ?>
</div>
<?php require 'bottom.php';?>