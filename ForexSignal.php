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
?>
<html>
<body>
<form method="post">
  Type:<br>
  <select name="type">
    <option value="BUY">BUY</option>
    <option value="SELL">SELL</option>
  </select>
  <br>
  Symbol:<br>
  <select name="symbol">
    <option value="EURJPY">EUR/JPY</option>
    <option value="EURUSD">EUR/USD</option>
    <option value="GBPUSD">GBP/USD</option>
    <option value="USDJPY">USD/JPY</option>
    <option value="USDCHF">USD/CHF</option>
    <option value="GBPJPY">GBP/JPY</option>
    <option value="EURCHF">EUR/CHF</option>
    <option value="AUDUSD">AUD/USD</option>
    <option value="USDCAD">USD/CAD</option>
    <option value="NZDUSD">NZD/USD</option>
    <option value="EURGBP">EUR/GBP</option>
    <option value="CHFJPY">CHF/JPY</option>
    <option value="GBPCHF">GBP/CHF</option>
    <option value="EURAUD">EUR/AUD</option>
    <option value="EURCAD">EUR/CAD</option>
    <option value="AUDCAD">AUD/CAD</option>
    <option value="AUDJPY">AUD/JPY</option>
    <option value="CADJPY">CAD/JPY</option>
    <option value="NZDJPY">NZD/JPY</option>
    <option value="GBPAUD">GBP/AUD</option>
    <option value="AUDNZD">AUD/NZD</option>
    <option value="EURNZD">EUR/NZD</option>
    <option value="GBPCAD">GBP/CAD</option>
    <option value="GBPNZD">GBP/NZD</option>
    <option value="AUDCHF">AUD/CHF</option>
    <option value="USDSEK">USD/SEK</option>
    <option value="EURSEK">EUR/SEK</option>
    <option value="EURNOK">EUR/NOK</option>
    <option value="USDNOK">USD/NOK</option>
    <option value="USDMXN">USD/MXN</option>
    <option value="USDZAR">USD/ZAR</option>
    <option value="NZDCAD">NZD/CAD</option>
    <option value="CADCHF">CAD/CHF</option>
    <option value="NZDCHF">NZD/CHF</option>
    <option value="USDTRY">USD/TRY</option>
    <option value="EURTRY">EUR/TRY</option>
    <option value="XAUUSD">XAU/USD</option>
    <option value="XAGUSD">XAG/USD</option>
    <option value="ZARJPY">ZAR/JPY</option>
    <option value="TRYJPY">TRY/JPY</option>
    <option value="ESP35">ESP35</option>
    <option value="GER30">GER30</option>
    <option value="US30">US30</option>
    <option value="NAS100">NAS100</option>
    <option value="AUS200">AUS200</option>
    <option value="FRA40">FRA40</option>
    <option value="UK100">UK100</option>
    <option value="SPX500">SPX500</option>
    <option value="UKOil">UKOil</option>
    <option value="USOil">USOil</option>
    <option value="JPN225">JPN225</option>
    <option value="Bund">Bund</option>
    <option value="Copper">Copper</option>
    <option value="EUSTX50">EUSTX50</option>
    <option value="NGAS">NGAS</option>
    <option value="BTCUSD">BTCUSD</option>
    <option value="LTCUSD">LTCUSD</option>
    <option value="ETHUSD">ETHUSD</option>
  </select>
  <br>
  Price:<br>
  <input type="text" name="price" value="">
  <br>
  Check:<br>
  <input type="text" name="addSignal" value="">
  <br><br>
  <input type="submit" value="Submit">
</form>

</body>
</html>
