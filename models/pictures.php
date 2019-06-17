<?php
require_once 'db.php';

class pictures extends database{

    public function add_pictures($path, $user_id){
        date_default_timezone_set('Europe/Paris');
        $data = array('path' => $path, 'date' => date('d/m/Y'), 'time' => date('H:i:s'), 'user_id' => $user_id);
        $pdo = $this->dbConnect();
        $sql = 'INSERT INTO `posts` (`path`, `date`, `time`, `id_user`) VALUES (:path, :date, :time, :user_id);';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($data);
    }
    public function removePost($post_id, $login){
        $pdo = $this->dbConnect();
        if (!($this->check_user($login)))
        {
            $sql = 'SELECT id_user, users.login, users.id, id_post, posts.path
                    FROM `posts`
                    JOIN `users` on users.id = posts.id_user
                    WHERE posts.id_post =?';
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array($post_id));
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result['login'] === $login){
                $sql = 'DELETE FROM `posts`
                        WHERE posts.id_post =?;';
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array($post_id));
                return ($result['path']);
            }
        }
    }
    public function get_dataPictures($id_user = 0, $offset = 0){
        $pdo = $this->dbConnect();
        $sql = 'SELECT posts.id_post, posts.path, posts.id_user, users.login, posts.date, posts.time, likes.id_user as user_liked,
        (SELECT comments.comment 
            FROM comments 
            WHERE comments.id_post = posts.id_post 
            ORDER BY comments.id_post 
            DESC LIMIT 1) AS comment,
        (SELECT users.login 
        FROM users 
        JOIN comments 
        ON comments.id_user = users.id 
        WHERE comments.id_post = posts.id_post 
        ORDER BY comments.id_post 
        DESC LIMIT 1) AS commenter,
        (SELECT COUNT(comments.id_post)
        FROM comments
        WHERE comments.id_post = posts.id_post) AS commentNbr,
        (SELECT COUNT(likes.id_post)
        FROM likes
        WHERE likes.id_post = posts.id_post) AS likesCount 
        FROM posts 
        JOIN users ON posts.id_user = users.id
        LEFT JOIN likes ON posts.id_post = likes.id_post
        AND likes.id_user = :id_user
        ORDER BY posts.id_post DESC
        LIMIT 5 OFFSET :offset';
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue('offset', intval($offset), PDO::PARAM_INT);
        $stmt->bindValue('id_user', intval($id_user), PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    public function get_pictures(){
        $pdo = $this->dbConnect();
        $sql = 'SELECT *
                FROM `posts`';
        $stmt = $pdo->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    public function getUser_pictures($user_id){
        $data = array($user_id);
        $pdo = $this->dbConnect();
        $sql = "SELECT posts.*,
                (SELECT COUNT(*) FROM comments
                        WHERE comments.id_post = posts.id_post) 
                        AS commentNbr,
                (SELECT COUNT(*) FROM likes
                        WHERE likes.id_post = posts.id_post)
                        AS LikeNbr
                        FROM `posts`
                WHERE `id_user`= ?
                ORDER BY `id_post` DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($data);
        $result = $stmt->fetchALl(PDO::FETCH_ASSOC);
        return $result;
    }
    public function check_user($user, $pdo = NULL){
        if (!(isset($pdo)))
            $pdo = $this->dbConnect();
        $value = array($user);
        $sql = 'SELECT COUNT(*) FROM `users` WHERE `login`=?;';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($value);
        $result = $stmt->fetch();
        if ($result[0] > 0){
            return FALSE;
        }else
            return TRUE;
    }
    public function getData_user($user, $pdo = NULL){
        if (!(isset($pdo)))
            $pdo = $this->dbConnect();
        $data = array($user);
        $sql = 'SELECT `id`, `login` FROM `users` WHERE `login`=?;';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($data);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
    public function addComment($comment, $post_id, $user_id, $login){
        $pdo = $this->dBConnect();
        if (!$this->check_user($login, $pdo)){
            $info = $this->getData_user($login, $pdo);
            if ($info['id'] === $user_id && $info['login'] === $login){
                if ($this->check_post($post_id)){
                    date_default_timezone_set('Europe/Paris');
                    $data = array('date' => date('d/m/Y'), 'time' => date('H:i:s'),'comment' => $comment, 'id_post' => $post_id, 'id_user' => $user_id);
                    $sql = "INSERT INTO `comments`(`date`, `time`, `comment`, `id_post` ,`id_user`)
                            VALUES (:date, :time, :comment, :id_post, :id_user);";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute($data);
                    $this->mail_picCommented($post_id, $login, $pdo);
                    return TRUE;
                }
                return FALSE;
            }
        }
        return FALSE;
     
    }
    public function check_post($post_id, $pdo = NULL){
        if (!(isset($pdo)))
            $pdo = $this->dbConnect();
        $value = array($post_id);
        $sql = 'SELECT COUNT(*) FROM `posts` WHERE `id_post`=?;';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($value);
        $result = $stmt->fetch();
        if ($result[0] > 0){
            return TRUE;
        }else
            return FALSE;
    }
    public function already_liked($post_id, $user_id){
        $pdo = $this->dbConnect();
        $sql = 'SELECT COUNT(*) FROM `likes`
                WHERE likes.id_post = ? AND likes.id_user = ?;';
        $value = array($post_id, $user_id);
        $stmt = $pdo->prepare($sql);
        $stmt->execute($value);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $liked = intval($result['COUNT(*)']);
        if ($liked > 0)
            return TRUE;
        else
            return FALSE;
    }
    public function add_like($post_id, $user_id, $login){
        $pdo = $this->dbConnect();
        if (!$this->check_user($login, $pdo)){
            $info = $this->getData_user($login, $pdo);
            if ($info['id'] === $user_id && $info['login'] === $login){
                if ($this->check_post($post_id)){
                    $data = array('id_post' => $post_id, 'id_user' => $user_id);
                    $sql = "INSERT INTO `likes` (`id_post`, `id_user`)
                            VALUES (:id_post, :id_user);";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute($data);
                    return TRUE;
                }
                return FALSE;
            }
        }
        return FALSE;
    }
    public function takeOffLike($post_id, $user_id){
        $pdo = $this->dbConnect();
        $sql = 'DELETE FROM `likes`
                WHERE likes.id_post = ?
                AND likes.id_user = ?;';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array($post_id, $user_id));
        return TRUE;
    }
    public function pic_comment($post_id){
        $pdo = $this->dbConnect();
        $sql = 'SELECT usersComment.login AS commenter, comments.date AS comment_date, comments.time AS comment_time,
         comments.comment, posts.path, posts.date AS post_date, posts.time AS post_time, userPost.login AS author, posts.id_post,
         (SELECT COUNT(*) FROM likes WHERE likes.id_post = ?) AS likescount
        FROM comments
        INNER JOIN posts ON posts.id_post = comments.id_post
        INNER JOIN users AS usersComment ON usersComment.id = comments.id_user
        INNER JOIN users AS userPost ON userPost.id = posts.id_user
        WHERE comments.id_post = ?;';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array($post_id, $post_id));
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    public function mail_picCommented($post_id, $login, $pdo){
        $data = array($post_id);
        $sql = 'SELECT users.mail, users.login, users.mail_notification
                FROM `posts`
                JOIN users ON posts.id_user = users.id
                WHERE posts.id_post = ?;';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($data);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result['mail_notification'] === '0' || $login === $result['login']){
            return TRUE;
        }
        $subject = $login." a commente votre photo";
        $entete = "From: Camagru@localhost.com";
        $message = "Hey ".$result['login'].",
        
        Venez répondre à la personne qui à commenté votre photo ! :)
        

        Ceci est un mail automatique, Merci de ne pas y repondre.";
        $status = mail($result['mail'], $subject, $message);
        if (!$status) {
            return FALSE;
        }
        else
            return TRUE;
    }
}
