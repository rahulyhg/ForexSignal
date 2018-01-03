<?php
session_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
if (isset($_REQUEST["username"]) && isset($_REQUEST["password"])) {
  if ($_REQUEST["password"] == date('dH')) {
    $_SESSION["login"] = true;
    print "login";
  } else {
    print "logout";
  }
  die;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login screen</title>
  <link rel="stylesheet" href="public/css/login.css">
</head>
<body>
<div class="wrapper">
  <div class="container">
    <h1>Welcome</h1>
    <form class="form">
      <input type="text" placeholder="Username" name="username">
      <input type="password" placeholder="Password" name="password">
      <button type="submit" id="login-button">Login</button>
    </form>
  </div>
  <ul class="bg-bubbles">
    <li></li>
    <li></li>
    <li></li>
    <li></li>
    <li></li>
    <li></li>
    <li></li>
    <li></li>
    <li></li>
    <li></li>
  </ul>
</div>
<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<script src="public/js/login.js"></script>
</body>
</html>
