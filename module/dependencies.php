<?php
use Dotenv\Dotenv;
use Pimple\Container;
use Monolog\Logger;
use Monolog\Handler\RotatingFileHandler;
use Module\config\DinoDb;
use Module\Dao\VoucherDao;

$dotenv = new Dotenv(__DIR__);
$dotenv->load();
$dotenv->required(['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS']);

$container = new Container();
$container['logger'] = function ($c) {
  $logger = new Logger('app');
  $logger->pushHandler(new RotatingFileHandler('log/app.log', 100, Logger::INFO));
  return $logger;
};

$container['pdoDino'] = function ($c) {
  $db = new DinoDb($c['logger']);
  return $db->getPdo();
};

$container['daoVoucher'] = function ($c) {
  return new VoucherDao($c['logger'], $c['pdoDino']);
};
