<?php require 'header.php';?>
<?php
$param = array();
$param['token']     = $_SESSION['token'];
$param['pay_token'] = $_POST['pay_token'];
$param['bankId']    = $_POST['bankId'];

$train = new TrainApi();
$train->method('train.pay.epay');
$data = $train->action($param);

if ( $data['errMsg'] == 'Y'){
    $data = $data['datas'];
    echo '<form name="postForm" id="postForm" method="post" action="'.$data['action'].'">';
    foreach ($data['formdata'] as $name=>$val){
        echo '<input type="hidden" name="'.$name.'" value="'.$val.'">';
    }
    echo '</form>';
    echo '<script>$("#postForm").submit();</script>';
} else {
    echo '<script>alert("'.$data['errMsg'].'");location.href = "order-no.php"; </script>';
}
exit();
?>
<?php require 'bottom.php';?>