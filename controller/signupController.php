<?php

if ($_POST['action'] === 'signup'){
    if (isset($_POST['login']) && isset($_POST['mail']) && isset($_POST['login']) && isset($_POST['pwd']) && isset($_POST['pwd2'])){
        require_once $_SERVER['DOCUMENT_ROOT'].'/models/users.php';
        $login = $_POST['login'];
        $mail = $_POST['mail'];
        $uppercase = preg_match('@[A-Z]@', $_POST['pwd']);
        $lowercase = preg_match('@[a-z]@', $_POST['pwd']);
        $number    = preg_match('@[0-9]@', $_POST['pwd']);
        if(!$uppercase || !$lowercase || !$number || strlen($_POST['pwd']) < 8) {
            echo "login";
            http_response_code(400);
            die();
        }
        $password = hash('sha512', $_POST['pwd']);
        $password2 = hash('sha512', $_POST['pwd2']);
        $user = new users;
        if (!preg_match('/^([A-Za-z]|[A-Za-z0-9]){5,31}$/', $login)){
            $errors_log['login'] = "regex Login";
        }
        if (!$user->check_user($login)){
            $errors_log['login'] = "Login déjà utilisé";
            http_response_code(401);
            exit();
        } if (!$user->check_mail($mail)){
            $errors_log['mail'] = "Mail invalide ou déjà utilisé";
            http_response_code(401);
            exit();
        } if ($password !== $password2){
            $errors_log['password'] = "Les mots de passes ne sont pas identiques";
        } if (!isset($errors_log)){
            $new_user = array('login' => $login, 'mail' => $mail , 'pwd' => $password);
            $pdo = new users;
            if ($stmt = $pdo->add_user($new_user) === TRUE){
                http_response_code(200);
                die();
            }
            else if ($stmt === FALSE){
                $errors_log['login'] = "Login/Email déjà utilisé";
            }
        }
        if (isset($errors_log)){
            print_r ($errors_log);
            http_response_code(400);
            exit();
        }
    } else{
        http_response_code(400); 
        exit();
    }
}  
