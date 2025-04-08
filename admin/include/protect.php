<?php //ouvre accès à l'utilisateur sur cette page
session_start(); // si identifiant et mot de passe ok
if (!isset($_SESSION['is_logged']) || $_SESSION['is_logged'] != "oui") { //alors
    header("Location:/admin/login.php"); //redirige vers la page login.php
    exit();
}
