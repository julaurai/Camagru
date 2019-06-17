<?php

if (isset($_SESSION['login'])){
    header("Locaton : index.php");
    exit();
}

if ($_POST['action'] === 'signin'){
    require_once $_SERVER['DOCUMENT_ROOT'].'/models/users.php';
    if (isset($_POST['login']) && isset($_POST['pwd'])){
        $login = $_POST['login'];
        $password = hash('sha512', $_POST['pwd']);
        $user = array('login' => $login, 'pwd' => $password);
        $pdo = new users;
        $check = $pdo->auth($user);
        $auth = $check['auth'];
        $active = $check['active'];
        if ($auth === '0'){
            http_response_code(400);
            exit();
        }
        else if ($auth === '1' && $active === '0'){
            http_response_code(428);
            exit();
        }
        else if ($active ==='1' && $auth === '1'){
            session_start();
            $_SESSION['login'] = $user['login'];
            $_SESSION['user_id'] = $check['user_id'];
            http_response_code(200);
            exit();
        }
        else{
            http_response_code(400);
            exit();
        }
    }
}
