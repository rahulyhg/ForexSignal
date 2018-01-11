<?PHP
$accountInfo = json_decode(file_get_contents(__DIR__ . '/../data/AccountInfo.txt'), true);
if (isset($_REQUEST['number'])) {
  $newInfo = [
    'broker' => $_REQUEST['broker'],
    'number' => $_REQUEST['number'],
    'name' => $_REQUEST['name'],
    'balance' => $_REQUEST['balance'],
    'profit' => $_REQUEST['profit']
  ];
  $accountInfo[$_REQUEST['number']] = $newInfo;
  file_put_contents(__DIR__ . '/../data/AccountInfo.txt', json_encode($accountInfo));
}
header("Refresh: 5; url=sendAccountInfo.php");
print json_encode($accountInfo);
?>