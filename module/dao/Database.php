<?php
namespace Module\Dao;

use PDO;

class Database {
    public static function getPdo() {
        $host = 'localhost';
        $user = 'root';
        $pass = 'root';
        $dbName = 'bookstore';
        $charset = 'utf8';

        $dsn = "mysql:host=$host;dbname=$dbName;charset=$charset";
        $opt = array(
            PDO::ATTR_ERRMODE               => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE    => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES      => false
        );
        try {
            return new PDO($dsn, $user, $pass, $opt);
        } catch (PDOException $e){
            error_log('getPdo: '.$e->getMessage());
            return false;
        }
    }
}