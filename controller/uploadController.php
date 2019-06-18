<?php
if (!isset($_SESSION['login']) && !isset($_SESSION['user_id'])){
    session_start();
}
if (isset($_POST['submit'])){
    $login = $_SESSION['login'];
    $user_id = $_SESSION['user_id'];

    $file = $_FILES['file'];
    $fileName = $_FILES['file']['name'];
    $fileTmpName = $_FILES['file']['tmp_name'];
    $fileSize = $_FILES['file']['size'];
    $fileError = $_FILES['file']['error'];
    $fileType = $_FILES['file']['type'];
    if ($fileTmpName !== 'error' && $fileSize !== 0){
        $fileExt = explode('.', $fileName);
        $fileActualExt = strtolower(end($fileExt));
        $allowed = array('jpg', 'png', 'jpeg');
        $info = getimagesize($fileTmpName);
    } else {
        header("Location: ../index.php?p=upload&uploaded=wrong");
        die();
    }
    if (in_array($fileActualExt, $allowed) && $fileError === 0 && filesize($fileTmpName) === $fileSize && $fileSize < 10000000){
            $fileNameNew = uniqid('', true).".".$fileActualExt;
            $destination = '../images/'.$login.$fileNameNew;
            $mime = mime_content_type($fileTmpName);
            $mime = explode('/', $mime);
            if (in_array($mime[1], $allowed) && $mime[0] === 'image'){
                if ($mime[1] === 'jpg'){
                    $img = imagecreatefromjpeg($fileTmpName);
                } else if ($mime[1] === 'png'){
                    $img = imagecreatefrompng($fileTmpName);
                } else if ($mime[1] === 'jpeg'){
                    $img = imagecreatefromjpeg($fileTmpName);
                }
                require_once $_SERVER['DOCUMENT_ROOT'].'/models/pictures.php';
                $new = imagecreatetruecolor(640, 480);
                var_dump(imagecopyresized($new, $img, 0, 0, 0, 0, 640, 480, imagesx($img), imagesy($img)));
                imagejpeg($new, $destination);
                $_SESSION['pic'] = $destination;
                header("Location: ../index.php?p=upload&uploaded=success");
            } else {
                header("Location: ../index.php?p=upload&uploaded=wrong");
            }
        } else {
            header("Location: ../index.php?p=upload&uploaded=wrong");
        }
        
    }
    else {
        header("Location: ../index.php?p=upload&uploaded=wrong");
}

