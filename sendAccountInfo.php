<?PHP
$accountInfo = json_decode(file_get_contents('data/AccountInfo.txt'), true);
if (isset($_REQUEST['number'])) {
  $newInfo = [
    'broker' => $_REQUEST['broker'],
    'number' => $_REQUEST['number'],
    'name' => $_REQUEST['name'],
    'balance' => $_REQUEST['balance'],
    'profit' => $_REQUEST['profit']
  ];
  $accountInfo[$_REQUEST['number']] = $newInfo;
  file_put_contents('data/AccountInfo.txt', json_encode($accountInfo));
}
print json_encode($accountInfo);
?>