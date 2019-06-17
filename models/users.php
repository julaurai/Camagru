<?php
require_once 'db.php';

class users extends database{

    public function add_user($new_user){
        $pdo = $this->dbConnect();
        if ($this->check_user($new_user['login'], $pdo) && $this->check_mail($new_user['mail'], $pdo)){
            $sql = 'INSERT INTO `users` (`login`, `mail`, `password`) VALUES (:login, :mail, :pwd);';
            $stmt = $pdo->prepare($sql);
            $stmt->execute($new_user);
            if ($this->mailConfirmation($new_user['login'], $new_user['mail']))
                return TRUE;
            else
                return FALSE;
        }
        else
            return FALSE;
    }
    private function mailConfirmation($login, $mail){
        $pdo = $this->dbConnect();
        $key = md5(microtime(TRUE)*100000);
        $data = array($key, $login);
        $sql = 'UPDATE `users` SET `key`=? WHERE `login`=?;';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($data);
        $subject = "Activer votre compte";
        $entete = "From: Camagru@localhost.com";
        $message = "Bienvenue sur Camagru,
        
        Pour activer votre compte, veuillez cliquer sur le lien ci dessous
        ou copier/coller l'url dans votre navigateur internet.
        http://localhost:8080/index.php?log=".urlencode($login).'&key='.urlencode($key)."
        

        Ceci est un mail automatique, Merci de ne pas y repondre.";
        $status = mail($mail, $subject, $message);
        if (!$status) {
            return FALSE;
        }
        else
            return TRUE;
    }
    public function auth($user){
        $pdo = $this->dbConnect();
        $response = array('auth' => "0",'active' => "0", 'user_id' => "0");
        if (!$this->check_user($user['login'], $pdo)){
            $sql = 'SELECT * FROM `users` WHERE `login`= ?;';
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array($user['login']));
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user['pwd'] === $result['password']){
                $response['user_id'] = $result['id'];
                if ($result['active'] == 0){  
                    $response['auth'] = "1";
                    $response['active'] = "0";
                    return $response;  
                }
                else if ($result['active'] == 1){
                    $response['auth'] = "1";
                    $response['active'] = "1";
                    return $response;
                }
                // else {
                //     $response['auth'] = "1";
                //     $response['active'] = "1";
                //     return $response;
                // }
            }
        }
        else
            return $response;
    }
    public function check_user($user, $pdo = NULL){
        if (!(isset($pdo)))
            $pdo = $this->dbConnect();
        $value = array($user);
        $sql = 'SELECT * FROM `users` WHERE `login`=?;';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($value);
        $result = $stmt->fetch();
        if (strtolower($result['login']) === strtolower($user) ){
            return FALSE;
        }else
            return TRUE;
    }
    public function check_mail($mail, $pdo = NULL){
        if (!(isset($pdo)))
            $pdo = $this->dbConnect();
            if (!(filter_var($mail, FILTER_VALIDATE_EMAIL))){
                return FALSE;
            }
        $value = array($mail);
        $sql = 'SELECT COUNT(*) FROM `users` WHERE `mail`=?;';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($value);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result['COUNT(*)'] === "1"){
            return $result;
        }else
            return TRUE;
    }
    public function getData_user($user, $pdo = NULL){
        if (!(isset($pdo)))
            $pdo = $this->dbConnect();
        $data = array($user);
        $sql = 'SELECT `mail`, `active`, `key`, `mail_notification` FROM `users` WHERE `login`=?;';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($data);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
    public function getuser_key($mail, $pdo = NULL){
        if (!(isset($pdo)))
            $pdo = $this->dbConnect();
        $data = array($mail);
        $sql = 'SELECT `key` FROM `users` WHERE `mail`=?;';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($data);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
    public function modifyMail($user, $mail){
        $pdo = $this->dbConnect();
        if ($this->check_mail($mail, $pdo) === TRUE){
            $data = array($mail, $user);
            $sql = 'UPDATE `users` SET `mail`=? WHERE `login`=?;';
            $stmt = $pdo->prepare($sql);
            $stmt->execute($data);
            return TRUE;
        }
        else
            return FALSE;
    }
    public function modifyUser($user, $new_login){
        $pdo = $this->dbConnect();
        if ($this->check_user($new_login, $pdo)){
            $data = array($new_login, $user);
            $sql = 'UPDATE `users` SET `login`=? WHERE `login`=?;';
            $stmt = $pdo->prepare($sql);
            $stmt->execute($data);
            return TRUE;
        }
        else
            return FALSE;
    }
    public function modifyPwd($user, $pwd){
        $pdo = $this->dBconnect();
        $data = array($pwd, $user);
        $sql = 'UPDATE `users` SET `password`=? WHERE `login`=?;';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($data);
        return TRUE;
    }
    public function tokencheck($user, $token){
        if (!$this->check_user($user)){
            $data = $this->getData_user($user);
            if ($data['key'] === $token && $data['active'] === "0"){
                $key = md5(microtime(TRUE)*100000);
                $pdo = $this->dbConnect();
                $data = array("1",$key, $user);
                $sql = 'UPDATE `users` SET `active`=?, `key`=? WHERE `login`=?;';
                $stmt = $pdo->prepare($sql);
                $stmt->execute($data);
                return TRUE;
            }
            else
                return FALSE;
        }
        else
            return FALSE;
    }
    public function mail_recovery($mail){
        $pdo = $this->dbConnect();
        $data = array($mail);
        $sql = 'SELECT * FROM `users` WHERE `mail`=?;';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($data);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if (isset($result['key'])){
            $key = $result['key'];
        } else
            exit();
        $subject = "Reinitialisation mot de passe";
        $entete = "From: Camagru@localhost.com";
        $message = "Bienvenue sur Camagru,
        
        Pour reinitialiser votre compte, veuillez cliquer sur le lien ci dessous
        ou copier/coller l'url dans votre navigateur internet.
        http://localhost:8080/index.php?mail=".urlencode($mail).'&key='.urlencode($key)."
        

        Ceci est un mail automatique, Merci de ne pas y repondre.";
        mail($mail, $subject, $message);
    }
    public function init_password($new_pwd, $mail){
        $pdo = $this->dBconnect();
        $key = md5(microtime(TRUE)*100000);
        $data = array($new_pwd, $key, $mail);
        $sql = 'UPDATE `users` SET `password`=?, `key` =? WHERE `mail`=?;';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($data);
        return TRUE; 
    }
    public function mail_notification($response, $login){
        $pdo = $this->dbConnect();
        $data = array($response, $login);
        $sql = 'UPDATE `users` SET `mail_notification` = ? WHERE `login`= ?;';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($data);
        return TRUE;
    }
}
