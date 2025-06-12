<?php

try {
    $db = new PDO("mysql:host=localhost;dbname=club-canin2;charset=utf8", "root", "");
} catch (Exception $e) {
    die($e->getMessage());
}
