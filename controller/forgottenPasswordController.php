<?php

if (isset($_POST['mail'])){
    require_once $_SERVER['DOCUMENT_ROOT'].'/models/users.php';
    $mail = $_POST['mail'];
    $pdo = new users;
    $pdo->mail_recovery($mail);
    http_response_code(200);
    die();
}


