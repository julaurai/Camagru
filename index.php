<?php
session_start();
ob_start();

if (isset($_GET['p'])){
    $p = $_GET['p'];
    if ($p === 'profil'){
        $title = 'Profil';
        require_once 'pages/profil.php';
    } else if ($p === 'webcam'){
        $title = 'Webcam';
        require_once 'pages/webcam.php';
    } else if ($p === 'upload'){ 
        if (isset($_SESSION['login'])){
            $title = 'Webcam';
            require_once 'pages/upload.php';
        } else {
            $title = 'Gallery';
            header("Location: index.php");
        }
    }else if ($p === 'posts'){
        $title = 'Posts';
        if (isset($_GET['postID'])){
            require_once 'pages/postid.php';
        } else{
            require_once 'pages/posts.php';
        }
    } else if ($p === 'signup'){
        if (isset($_SESSION['login'])){
            $title = 'Gallery';
            header('Location: index.php');
        }
        require_once 'pages/signup.php';
        $title = 'Signup';
    } else if ($p === 'signin'){
        if (isset($_SESSION['login'])){
            $title = 'Gallery';
            header('Location: index.php');
        }
        require_once 'pages/signin.php';
        $title = 'Signin';
    } else if ($p === 'forgottenPassword'){
        if (isset($_SESSION['login'])){
            $title = 'Gallery';
            header("Location: index.php");
        } else {
            $title = 'ForgottenPassword';
            require_once 'pages/forgottenPassword.php';
        }
    } else {
        $title = 'Gallery';
        header("Location: index.php");
    }
} else if (isset($_GET['log']) && isset($_GET['key'])){
    if (isset($_SESSION['login'])){
        $title = 'Gallery';
        header("Location: index.php");
    } else {
        $title = "Signup";
        require_once 'controller/mailconfirmationController.php';
    }
} else if (isset($_GET['mail']) && (isset($_GET['key']))){
    if (isset($_SESSION['login'])){
        $title = 'Gallery';
        header("Location: index.php");
    } else {
        $title = 'ForgottenPassword';
        require_once 'controller/password_recoveryController.php';
    }
} else {
    $title = 'Gallery';
    require_once 'pages/main.php';
}
$content = ob_get_clean();
require_once 'pages/template/default.php';

