<?php

require_once './config/config.php';

class Model {
    protected $db;

    function __construct(){
        $this->createDatabase();
        $this->db = new PDO('mysql:host=' . HOST . ';dbname=' . DB . ';charset=utf8', USER, PASS);
        $this->deploy();
    }

    private function createDatabase(){
        $pdo = new PDO('mysql:host' . HOST, USER, PASS);
        $pdo->exec('CREATE DATABASE IF NOT EXISTS ' . DB);
    }

    public function deploy(){
        $query = $this->db->query('SHOW TABLES');
        $tables = $query->fetchAll();
        if(count($tables) == 0){
            $sql = file_get_contents('./database/schema.sql');
            $this->db->query($sql);
        }
    }
}