<?php
date_default_timezone_set('asia/ho_chi_minh');
file_put_contents('data/ForexSignal.txt', '');
$addSignal = date('dH');
if (isset($_REQUEST['addSignal']) && ($_REQUEST['addSignal'] == $addSignal)) {
  $newSignal[] = $_REQUEST['type'];
  $newSignal[] = $_REQUEST['symbol'];
  $newSignal[] = $_REQUEST['price'];
  $newSignal[] = rand(1, 100000);
  $data = implode(',', $newSignal);
  file_put_contents('data/ForexSignal.txt', $data);
  header("Location: data/ForexSignal.txt");
}
