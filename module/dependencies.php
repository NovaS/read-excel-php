<?php
use Dotenv\Dotenv;
use Pimple\Container;
use Monolog\Logger;
use Monolog\Handler\RotatingFileHandler;
use Module\config\DinoDb;
use Module\Dao\DinoVoucherDao;

$dotenv = new Dotenv(__DIR__);
$dotenv->load();
$dotenv->required(['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS']);

$container = new Container();
$container['logger'] = function ($c) {
  $logger = new Logger('app');
  $logger->pushHandler(new RotatingFileHandler('logs/app.log', 100, Logger::INFO));
  return $logger;
};

$container['pdoDino'] = function ($c) {
  $db = new DinoDb($c['logger']);
  return $db->getPdo();
};

$container['daoDinoVoucher'] = function ($c) {
  return new DinoVoucherDao($c['logger'], $c['pdoDino']);
};
