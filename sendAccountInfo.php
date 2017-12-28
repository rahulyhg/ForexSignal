<?PHP
$directory = 'data/accountInfo';
if (isset($_REQUEST['number'])) {
  $number = $_REQUEST['number'];
  $broker = $_REQUEST['broker'];
  $name = $_REQUEST['name'];
  $balance = $_REQUEST['balance'];
  $profit = $_REQUEST['profit'];
  $accountInfo = [
    'broker' => $broker,
    'number' => $number,
    'name' => $name,
    'balance' => $balance,
    'profit' => $profit,
  ];
  file_put_contents("$directory/$number.txt", json_encode($accountInfo));
}
$scannedDirectory = array_diff(scandir($directory), array('..', '.'));
print json_encode($scannedDirectory);
?>