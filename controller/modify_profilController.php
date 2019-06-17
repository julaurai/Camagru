<?php

require_once ('../models/users.php');
session_start();
if (!isset($_SESSION['login']) && (!isset($_SESSION['user_id']))){
    http_response_code(404);
    exit();
}
$login = $_SESSION['login'];
$user_id = $_SESSION['user_id'];
if (isset($_POST['action'])){
    if ($_POST['action'] === 'pwd_modify'){
        $pdo = new users;
        $user = array('login' => $login, 'pwd' => hash('sha512', $_POST['old_pwd']));
        if ($pdo->auth($user)){
            if ($_POST['pwd'] === $_POST['pwd2']){
                $password = $_POST['pwd'];
                $uppercase = preg_match('@[A-Z]@', $password);
                $lowercase = preg_match('@[a-z]@', $password);
                $number    = preg_match('@[0-9]@', $password);
                if(!$uppercase || !$lowercase || !$number || strlen($password) < 8) {
                    http_response_code(400);
                    echo "pwd_regex";
                    die();
                }
                $pdo->modifyPwd($login, hash('sha512', $_POST['pwd']));
                http_response_code(200);
                die();
            }
            else{
                http_response_code(400);
                exit();
            }
        }
        else{
            http_response_code(401);
            die();
        }
    } else if ($_POST['action'] === 'login_modify'){
        if ($_POST['new_login'] === $login){
            echo "C'est déjà votre login :)";
            http_response_code(401);
            exit();
        }
        $pdo = new users;
        if ((preg_match('/^([A-Za-z]|[A-Za-z0-9]){5,31}$/', $_POST['new_login'])) === 0){
            echo "Votre login doit contenir entre 5 et 31 carectères avec uniquement des caractères alphanumériques";
            http_response_code(400);
            die();
        }
        if ($pdo->modifyUser($login, $_POST['new_login'])){
            $_SESSION['login'] = $_POST['new_login'];
            http_response_code(200);
            die();
        }
        else{
            echo "Login dèjà utilisé";
            http_response_code(401);
            die();
        }
    } else if ($_POST['action'] === 'mail_modify'){
        $pdo = new users;
        $mail = $_POST['mail'];
        if (!(filter_var($mail, FILTER_VALIDATE_EMAIL))){
            echo "Adresse mail invalide";
            http_response_code(400);
            die();
        }
        else if ($pdo->modifyMail($login, $_POST['mail'])){
            http_response_code(200);
            die();
        }else {
            http_response_code(401);
            die();
        }
    } else if ($_POST['action'] === 'mail_notification'){
        if (isset($_POST['Notif'])){
            if ($_POST['Notif'] === 'true'){
                $pdo = new users;
                if ($pdo->mail_notification('1', $login)){
                    http_response_code(200);
                    die();
                }
            } else if ($_POST['Notif'] === 'false'){
                $pdo = new users;
                if ($pdo->mail_notification('0', $login)){
                    http_response_code(200);
                    die();
                }
            } else{
                http_response_code(400);
                die();
            }
        } else {
            http_response_code(400);
            die();
        }
    }
}
