<?php
namespace Module\config;

use PDO;

class DinoDb {
  protected $logger;
  protected $host;
  protected $name;
  protected $user;
  protected $pass;

  public function __construct($log) {
    $this->logger = $log;
    $this->host = getenv('DB_HOST');
    $this->name = getenv('DB_NAME');
    $this->user = getenv('DB_USER');
    $this->pass = getenv('DB_PASS');
  }

  public function getPdo() {
    $dsn = "mysql:host=$this->host;dbname=$this->name";
    $opt = array(
      PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES   => false
    );
    try {
      return new PDO($dsn, $this->user, $this->pass, $opt);
    } catch (PDOException $e){
      $this->logger->error('DinoDb-getPdo: '.$e->getMessage());
      return false;
    }
  }
}