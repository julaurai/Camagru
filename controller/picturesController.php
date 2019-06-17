<?php

if (!(isset($_SESSION['login']))){
    session_start();
}

 if (isset($_SESSION['login']) && isset($_SESSION['user_id'])){
     $login = $_SESSION['login'];
     $user_id = $_SESSION['user_id'];
     $possible_action = array('addLike', 'addPost', 'addComment', 'delPost', 'takeOffLike', 'addUploadedPost', 'GetNextPost');
     require_once('../models/pictures.php');
     $pdo = new pictures;
     if (isset($_POST['action']) && in_array($_POST['action'], $possible_action)){
        $action = $_POST['action'];
        if (isset($_POST['user_id']) && $_POST['user_id'] !== $user_id || isset($_POST['login']) && ($_POST['login'] !== $login)){
            http_response_code(400);
            exit();
        } if (isset($_POST['post_id'])){
            $post_id = intval($_POST['post_id']);
            if ($post_id === 0 || !is_numeric($post_id)){
                http_response_code(400);
                exit();
            }
        } if ($action === 'addLike'){
            $pdo->add_like($post_id, $user_id, $login);
            http_response_code(200);
            exit();
        } else if ($action === 'delPost'){
                if ($_POST['user_id'] === $user_id){
                    $result = ($pdo->removePost($post_id, $login));
                    if ($result){
                        unlink($result);
                        http_response_code(200);
                        exit();
                    }
                }
                else{
                    http_response_code(400);
                    exit();
                }

        } else if ($action === 'addComment'){
            if (!empty($_POST['comment'])){
                $comment = $_POST['comment'];
            } else if (empty($_POST['comment'])){
                http_response_code(400);
                exit();
            }
            if ($pdo->addComment($comment, $post_id, $user_id, $login)){
                echo (htmlspecialchars($comment));
                http_response_code(200);
                exit();
            }
            else{
                http_response_code(400); 
                exit();
            }
        } else if ($action === 'addPost'){
            if (isset($_POST['data']) && isset($_POST['sticker'])){
                $dataPic = json_decode($_POST['sticker'], TRUE);
                $sticker = pathinfo($dataPic['src']);
                $sticker = $sticker['basename'];
                if(!file_exists("../images/stickers/". $sticker)){
                    http_response_code(400);
                    exit();
                } else {
                    $sticker = "../images/stickers/". $sticker;
                    define('UPLOAD_DIR', '../images/');
                    // IMG PROCESS
                    $img = $_POST['data'];
                    $img = str_replace('data:image/png;base64,', '', $img);
                    $img = str_replace(' ', '+', $img);
                    $data = base64_decode($img);
                    $file = UPLOAD_DIR . $login .uniqid('', true).'.png';
                    file_put_contents($file, $data);
     
                    $img = imagecreatefrompng($file);
                    $sticker = imagecreatefrompng($sticker);
                    imageflip($img, IMG_FLIP_HORIZONTAL);
                    
                    //imagecopyresampled($test, $img, 0, 0, 0, 0, 640, 480, 640, 480);
                    $sticker_x = intval($dataPic['width']);
                    $sticker_y = intval($dataPic['height']);
                    $stick = imagecreatetruecolor($sticker_x, $sticker_y);
                    imagecopyresized($stick, $sticker, 0, 0, 0, 0, $sticker_x, $sticker_y, imagesx($sticker), imagesy($sticker));
                    imagecolortransparent($stick, imagecolorat($stick, 0, 0));
                  
                    $x = round(intval($dataPic['x']));
                    $y = round(intval($dataPic['y']));
                 
                    imagecopyresized($img, $stick, $x, $y, 0, 0, $sticker_x, $sticker_y, imagesx($stick) , imagesy($stick));
                    imagepng($img, $file);
                    $pdo = new pictures;
                    $pdo->add_pictures($file, $user_id);
                    echo ($file);
                    http_response_code(200);
                    exit();
                    }
                }
        } else if ($action === 'addUploadedPost'){
            if (isset($_POST['data']) && isset($_POST['sticker'])){
                $dataPic = json_decode($_POST['sticker'], TRUE);
                $sticker = pathinfo($dataPic['src']);
                $sticker = $sticker['basename'];
                $file = $_POST['data'];
                $file = "../images/". (pathinfo($file)['basename']);
                if (!file_exists($file)){
                    http_response_code(400);
                    exit();
                }
                if(!file_exists("../images/stickers/". $sticker)){
                    http_response_code(400);
                    exit();
                } else {
                    $sticker = "../images/stickers/". $sticker;
                    define('UPLOAD_DIR', '../images/');
                    $mime = mime_content_type($file);
                    $allowed = array('jpg', 'png', 'jpeg');
                    $mime = explode('/', $mime);
                    if (in_array($mime[1], $allowed) && $mime[0] === 'image'){
                        if ($mime[1] === 'jpg'){
                            $img = imagecreatefromjpeg($file);
                        } else if ($mime[1] === 'png'){
                            $img = imagecreatefrompng($file);
                        } else if ($mime[1] === 'jpeg'){
                            $img = imagecreatefromjpeg($file);
                        }
                    require_once $_SERVER['DOCUMENT_ROOT'].'/models/pictures.php';
                    $sticker = imagecreatefrompng($sticker);
                    $sticker_x = intval($dataPic['width']);
                    $sticker_y = intval($dataPic['height']);
                    $stick = imagecreatetruecolor($sticker_x, $sticker_y);

                    imagecopyresized($stick, $sticker, 0, 0, 0, 0, $sticker_x, $sticker_y, imagesx($sticker), imagesy($sticker));
                    imagecolortransparent($stick, imagecolorat($stick, 0, 0));

                    $x = round(intval($dataPic['x']));
                    $y = round(intval($dataPic['y']));

                    imagecopyresized($img, $stick, $x, $y, 0, 0, $sticker_x, $sticker_y, imagesx($stick) , imagesy($stick));
                    imagepng($img, $file);
                    $pdo = new pictures;
                    $pdo->add_pictures($file, $user_id);
                    echo ($file);
                    unset($_SESSION['pic']);
                    http_response_code(200);
                    exit();
                    } else {
                        http_response_code(400);
                        exit();
                    }
                }
            }
        } else if ($action === 'takeOffLike'){
            var_dump($pdo->takeOffLike($post_id, $user_id));
            http_response_code(200);
            exit();
        } else if ($action === 'GetNextPost'){
            if (!isset($offset)){
                $offset = 0;
            }
            $offset = intval($_POST['offset']);
            $pdo = new pictures;
            $result = $pdo->get_dataPictures($user_id, $offset);
            $result['connected'] = TRUE;
            $result['session'] = array($login,$user_id);
            echo (json_encode($result));
            http_response_code(200);
            exit();
        } else {
            http_response_code(400);
            die();
        }
    }
} else if (isset($_POST['action']) && $_POST['action'] === 'GetNextPost'){
    require_once('../models/pictures.php');
    $offset = intval($_POST['offset']);
    $pdo = new pictures;
    $result = $pdo->get_dataPictures(0,$offset);
    $result['connected'] = FALSE;
    echo (json_encode($result));
    http_response_code(200);
    exit();
} else {
    header('HTTP/1.0 403 Forbidden');
    exit();
}
