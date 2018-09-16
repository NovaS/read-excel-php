<?php
date_default_timezone_set("Asia/Jakarta");
require __DIR__ . '/vendor/autoload.php';
require __DIR__.'/module/dependencies.php';

use Port\Excel\ExcelReader;
use Module\Util\Timer;

$serviceid = getenv('VOUCHER_SERVICE_ID'); 
$filename = getenv('VOUCHER_FILE_EXCEL');
$file = new \SplFileObject($filename);
$reader = new ExcelReader($file);
$dao = $container['daoVoucher'];
if(isset($reader) && isset($dao)) {
    $reader->setColumnHeaders(['VOUCHER']);
    $reader->setHeaderRowNumber(0);
    $timer = new Timer(1);
    $starttime = $timer->get();
    $elapsedtime = $starttime;
    for($r=1; $r<=$reader->count(); $r++) {
        $row = $reader->getRow($r);
        $code = trim($row['VOUCHER']);
        $count = $dao->insert($serviceid,$code);
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