<?php
session_start();
if (!isset($_SESSION["is_logged"]) || $_SESSION["role_id"] != 1) {
    header("Location: /unauthorized.php");
    exit;
}
