<?PHP
function sendMessage($message)
{
  $content = array(
    "en" => $message
  );

  $fields = array(
    'app_id' => "23e68763-a5e1-411a-8e79-1d4d09ad8b98",
    'included_segments' => array('All'),
    'data' => array("foo" => "bar"),
    'contents' => $content
  );

  $fields = json_encode($fields);
  print("\nJSON sent:\n");
  print($fields);

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
    'Authorization: Basic MjdkODI5MDEtZDZmZC00NTgzLWEwN2UtZmVlNDRmMDU0Y2I2'));
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($ch, CURLOPT_HEADER, FALSE);
  curl_setopt($ch, CURLOPT_POST, TRUE);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

  $response = curl_exec($ch);
  curl_close($ch);

  return $response;
}

$response = sendMessage($_REQUEST['mess']);
$return["allresponses"] = $response;
$return = json_encode($return);

print("\n\nJSON received:\n");
print($return);
print("\n");
?>