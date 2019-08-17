<?php
session_start();
unset($_SESSION['username']);
unset($_SESSION['token']);
header('location:index.php');