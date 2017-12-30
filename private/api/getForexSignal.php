<?php
date_default_timezone_set('asia/ho_chi_minh');

if (isset($_REQUEST['isDelete']) && $_REQUEST['isDelete']) {
} else {
  file_put_contents(__DIR__ . '/../data/forexSignal.txt', '');
}
$addSignal = date('dH');
if (isset($_REQUEST['addSignal']) && ($_REQUEST['addSignal'] == $addSignal)) {
  $newSignal[] = $_REQUEST['type'];
  $newSignal[] = $_REQUEST['symbol'];
  $newSignal[] = $_REQUEST['price'];
  $newSignal[] = rand(1, 100000);
  $data = implode(',', $newSignal);
  file_put_contents(__DIR__ . '/../data/forexSignal.txt', $data);
  header("Location: ../data/forexSignal.txt");
}
