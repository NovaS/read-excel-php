<?php
namespace Module\Dao;

use PDO;

class VoucherDao {
  private $logger;
  private $pdo;
  private $table_name;

  public function __construct($logger, $pdo) {
    $this->logger = $logger;
    $this->pdo = $pdo;
    $this->table_name = 'dvoucher';
  }

  public function getCount($serviceid=null) {
    if($serviceid==null) return false;
    try {
      $stmt = $this->pdo->prepare("SELECT count(*) total FROM $this->table_name WHERE voucher_serviceid=:serviceid");
      $stmt->bindParam(':serviceid',$serviceid);
      $stmt->execute();
      $result = $stmt->fetch();
      $stmt = null;
      return $result;
    } catch (PDOException $e) {
        $this->logger->error('VoucherDao-getCount: '.$e->getMessage());
        return null;
    }
  }

  public function findByVoucher($voucher=null) {
    if($voucher==null) return false;
    try {
      $sql = "SELECT * FROM $this->table_name WHERE voucher_voucher=:voucher";
      $stmt = $this->pdo->prepare($sql);
      $stmt->bindParam(':voucher',$voucher);
      $stmt->execute();
      $result = $stmt->fetch();
      $stmt = null;
      return $result;
    } catch (PDOException $e) {
        $this->logger->error('VoucherDao-findByVoucher: '.$e->getMessage());
        return null;
    }
  }

  public function insert($serviceid=null, $voucher=null, $msisdn=null, $moid=null) {
    if($serviceid==null || $voucher==null) return false;
    if($msisdn==null) $msisdn = '';
    if($moid==null) $moid = '';
    try {
      $sql = "INSERT INTO $this->table_name (voucher_serviceid, voucher_voucher, voucher_msisdn, voucher_moid, voucher_prosestime, voucher_expiretime, voucher_usage, voucher_status) ";
      $sql.= "VALUES (:serviceid,:voucher,:msisdn,:moid,NOW(),NOW(),0,0)";
      $stmt = $this->pdo->prepare($sql);
      $stmt->bindParam(':serviceid',$serviceid);
      $stmt->bindParam(':voucher',$voucher);
      $stmt->bindParam(':msisdn',$msisdn);
      $stmt->bindParam(':moid',$moid);
      $stmt->execute();
      $result = $stmt->rowCount();
      $stmt = null;
      return $result;
    } catch (PDOException $e) {
        $this->logger->error('VoucherDao-insert: '.$e->getMessage());
        return null;
    }
  }
}