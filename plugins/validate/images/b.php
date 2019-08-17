<?php
$html = file_get_contents('unchecked.gif');
echo base64_encode($html);
?>