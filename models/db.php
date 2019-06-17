<?php

class database{
    
    private $config;

    function __construct(){
        require $_SERVER['DOCUMENT_ROOT'].'/config/database.php';
        $this->config = $dbConfig;
    }

    public function dbConnect(){
        try {
            $db = new PDO($this->config['db_dsn'], $this->config['db_user'], $this->config['db_password']);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $db;
        }
        catch(PDOException $e)
        {
            die('Erreur : '.$e->getMessage());
        }
    }
}
