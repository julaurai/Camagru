<?php
if ($_SERVER['REQUEST_METHOD']) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}
require_once 'database.php';
try
{
    $db = new PDO('mysql:host=127.0.0.1', $dbConfig['db_user'], $dbConfig['db_password']);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "DROP DATABASE IF EXISTS`camagru`;";
    $db->exec($sql);
    $sql = "CREATE DATABASE `camagru`;
            USE `camagru`;
            ALTER DATABASE `camagru` CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci;
            DROP TABLE IF EXISTS `users`;
            DROP TABLE IF EXISTS `posts`;
            DROP TABLE IF EXISTS `comments`;
            CREATE TABLE `users`
            (
                `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
                `login` VARCHAR(32) UNIQUE KEY NOT NULL,
                `mail` VARCHAR(32) UNIQUE KEY NOT NULL,
                `password` CHAR(128),
                `key` VARCHAR(32),
                `active` TINYINT DEFAULT 0,
                `mail_notification` TINYINT DEFAULT 1
            );";
    $db->exec($sql);
    print ('DB "camagru" created'."\n".'TABLE "users" created'."\n");

    $sql =      "CREATE TABLE `posts`
                (
                    `id_post` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
                    `path` VARCHAR(128) NOT NULL,
                    `date` CHAR(10) NOT NULL,
                    `time` CHAR(10) NOT NULL,
                    `id_user` INT UNSIGNED,
                    FOREIGN KEY (id_user) REFERENCES users(id) ON DELETE CASCADE
                );";    
    $db->exec($sql);
    print ('TABLE "posts" created'."\n");
    $sql =      "CREATE TABLE `comments`
                (
                    `id_comments` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
                    `date` CHAR(10) NOT NULL,
                    `time` CHAR(10) NOT NULL,
                    `comment` VARCHAR(200) NOT NULL,
                    `id_post` INT UNSIGNED NOT NULL,
                    `id_user` INT UNSIGNED NOT NULL,
                    FOREIGN KEY (id_post) REFERENCES posts(id_post) ON DELETE CASCADE,
                    FOREIGN KEY (id_user) REFERENCES users(id) ON DELETE CASCADE
                );";
    $db->exec($sql);
    print('TABLE "comments" created'."\n");
    $sql =          "CREATE TABLE `likes`
                    (
                        `id_likes` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
                        `id_post` INT UNSIGNED NOT NULL,
                        `id_user` INT UNSIGNED NOT NULL,
                        FOREIGN KEY (id_post) REFERENCES posts(id_post) ON DELETE CASCADE,
                        FOREIGN KEY (id_user) REFERENCES users(id) ON DELETE CASCADE
                    );";
    $db->exec($sql);
    print('TABLE "likes" created'."\n");
    $password = hash('sha512', 'Qwerty12');
    $key = md5(microtime(TRUE)*100000);
    $key2 = md5(microtime(TRUE)*100000);
    $key3 = md5(microtime(TRUE)*100000);

    $sql = "INSERT INTO `users` (`login`, `mail`, `password`, `key`, `active`)
            VALUES  ('Julien', 'julien@julien.com', '$password', '$key', '1' ),
                    ('Enrique', 'enrique@eric.com', '$password', '$key2', '1' ),
                    ('Michel', 'michel@michel.com', '$password', '$key3', '1' );";
    $db->exec($sql);
    $sql = "INSERT INTO `posts` (`path`,`date`,`time`,`id_user`)
            VALUES  ( '../images/yeah.png', '01/01/2019', '08:00:02', '2' ),
                    ( '../images/bg.png', '01/01/2019', '08:00:03', '3' );";
    $db->exec($sql);
}
catch(PDOException $e)
{
    die('Erreur : '.$e->getMessage());
} 
