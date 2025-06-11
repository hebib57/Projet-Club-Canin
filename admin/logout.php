<?php
session_start();
$_SESSION['is_logged'] = "";
session_destroy();
$_SESSION = [];
header("Location:login.php");
exit();
