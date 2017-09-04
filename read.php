<?php
require __DIR__ . '/vendor/autoload.php';
date_default_timezone_set("Asia/Jakarta");

use Port\Excel\ExcelReader;
use Module\Dao\Database;
use Module\Util\Timer;

$pdo = Database::getPdo();
$file = new \SplFileObject('voucher.xlsx');
$reader = new ExcelReader($file);

if(isset($reader) && isset($pdo)) {
    $reader->setColumnHeaders(['Coupons']);
    $reader->setHeaderRowNumber(0);
    $timer = new Timer(1);
    $starttime = $timer->get();
    $elapsedtime = $starttime;
    for($r=1; $r<=$reader->count(); $r++) {
        $row = $reader->getRow($r);
        $code = $row['Coupons'];
        try {
            $sql = "INSERT INTO dino_voucher (voucher_serviceid,voucher_msisdn,voucher_voucher,voucher_prosestime,voucher_expiretime,voucher_usage) ";
            $sql.= "VALUES ('2029','',:code,now(),str_to_date('2017-12-31 23:59:59','%Y-%m-%d %H:%i:%s'),0)";
            $query = $pdo->prepare($sql);
			$query->bindParam(':code',$code);
			$query->execute();
			$result = $pdo->lastInsertId();
			$query = null;
        } catch (PDOException $e) {
            print $e->getMessage().PHP_EOL;
        }
        if($r%1000===0) { // TODO: comment this section for faster performance
            $processtime = $timer->get() - $elapsedtime;
            print "Process $r takes $processtime".PHP_EOL;
            $elapsedtime = $timer->get();
        }
    }
    $endtime = $timer->get() - $starttime;
    print "Finished, time elapsed: $endtime".PHP_EOL;
} else {
    print 'Error!'.PHP_EOL;
}