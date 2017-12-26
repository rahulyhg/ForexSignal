<?php

class ZuluAPIs
{
  private $login = 'tramphantest';
  private $password = 'zrbfeest';
  private $url = 'http://tradingserver.zulutrade.com/';

  function cURL($url, $dataArray = array())
  {
    $url = $this->url . $url;
    if ($dataArray) {
      $url .= '?' . http_build_query($dataArray);
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_USERPWD, "{$this->login}:{$this->password}");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);

    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
  }

  function openMarket($dataArray)
  {
    $url = '/open/market';
    return $this->cURL($url, $dataArray);
  }

  function updateLimit($dataArray)
  {
    $url = '/update/limit';
    return $this->cURL($url, $dataArray);
  }

  function getOpen()
  {
    $url = 'getOpen';
    return $this->cURL($url);
  }

  function getHistory($dataArray)
  {
    $url = 'getHistory/';
    return $this->cURL($url, $dataArray);
  }

  function getProviderStatistics()
  {
    $url = 'getProviderStatistics/';
    return $this->cURL($url);
  }

  function getTradingConfiguration()
  {
    $url = 'getTradingConfiguration/';
    return $this->cURL($url);
  }

  function getInstruments()
  {
    $url = 'getInstruments/';
    return $this->cURL($url);
  }
}

$zuluApi = new ZuluAPIs();
$openMarketResult = null;
switch (@trim($_REQUEST['action'])) {
  case 'getOpen':
    $zuluApi->getOpen();
    break;
  case 'getHistory':
    $dataArray = [
      'startDate' => $startDate,
      'endDate' => $endDate
    ];
    $zuluApi->getHistory();
    break;
  case 'getProviderStatistics':
    $zuluApi->getProviderStatistics();
    break;
  case 'getTradingConfiguration':
    $zuluApi->getTradingConfiguration();
    break;
  case 'openMarket':
    $dataArray = [
      'currencyName' => trim($_REQUEST['currencyName']),
      'lots' => trim($_REQUEST['lots']),
      'buy' => trim($_REQUEST['buy']),
      'requestedPrice' => trim($_REQUEST['requestedPrice']),
      'uniqueId' => trim($_REQUEST['uniqueId'])
    ];
    $openMarketResult = $zuluApi->openMarket($dataArray);
    break;
  case 'updateLimit':
    $dataArray = [
      'currencyName' => trim($_REQUEST['currencyName']),
      'buy' => trim($_REQUEST['buy']),
      'brokerTicket' => trim($_REQUEST['brokerTicket']),
      'limitValue' => trim($_REQUEST['limitValue'])
    ];
    $zuluApi->updateLimit();
    break;
  case 'getInstruments':
    $zuluApi->getInstruments();
    break;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Bootstrap 3, from LayoutIt!</title>
  <meta name="description" content="Source code generated using layoutit.com">
  <meta name="author" content="LayoutIt!">
  <link href="../css/bootstrap.min.css" rel="stylesheet">
  <link href="../css/style.css" rel="stylesheet">
</head>
<body>

<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <div class="row">
        <div class="page-header">
          <h1>
            LayoutIt! <small>Interface Builder for Bootstrap</small>
          </h1>
        </div>
        <div class="col-md-4">
          <form role="form">
            <div class="form-group">
              <label for="currencyName">
                Currency Name
              </label>
              <select name="currencyName">
                <option value="EUR/JPY">EUR/JPY</option>
                <option value="EUR/USD">EUR/USD</option>
                <option value="GBP/USD">GBP/USD</option>
                <option value="USD/JPY">USD/JPY</option>
                <option value="USD/CHF">USD/CHF</option>
                <option value="GBP/JPY">GBP/JPY</option>
                <option value="EUR/CHF">EUR/CHF</option>
                <option value="AUD/USD">AUD/USD</option>
                <option value="USD/CAD">USD/CAD</option>
                <option value="NZD/USD">NZD/USD</option>
                <option value="EUR/GBP">EUR/GBP</option>
                <option value="CHF/JPY">CHF/JPY</option>
                <option value="GBP/CHF">GBP/CHF</option>
                <option value="EUR/AUD">EUR/AUD</option>
                <option value="EUR/CAD">EUR/CAD</option>
                <option value="AUD/CAD">AUD/CAD</option>
                <option value="AUD/JPY">AUD/JPY</option>
                <option value="CAD/JPY">CAD/JPY</option>
                <option value="NZD/JPY">NZD/JPY</option>
                <option value="GBP/AUD">GBP/AUD</option>
                <option value="AUD/NZD">AUD/NZD</option>
                <option value="EUR/NZD">EUR/NZD</option>
                <option value="GBP/CAD">GBP/CAD</option>
                <option value="GBP/NZD">GBP/NZD</option>
                <option value="AUD/CHF">AUD/CHF</option>
                <option value="USD/SEK">USD/SEK</option>
                <option value="EUR/SEK">EUR/SEK</option>
                <option value="EUR/NOK">EUR/NOK</option>
                <option value="USD/NOK">USD/NOK</option>
                <option value="USD/MXN">USD/MXN</option>
                <option value="USD/ZAR">USD/ZAR</option>
                <option value="NZD/CAD">NZD/CAD</option>
                <option value="CAD/CHF">CAD/CHF</option>
                <option value="NZD/CHF">NZD/CHF</option>
                <option value="USD/TRY">USD/TRY</option>
                <option value="EUR/TRY">EUR/TRY</option>
                <option value="XAU/USD">XAU/USD</option>
                <option value="XAG/USD">XAG/USD</option>
                <option value="ZAR/JPY">ZAR/JPY</option>
                <option value="TRY/JPY">TRY/JPY</option>
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
            </div>
            <div class="form-group">
              <label for="lots">
                Lots
              </label>
              <input type="text" name="lots" class="form-control" value="0.1">
            </div>
            <div class="checkbox">
              <label>
                <input type="checkbox" name="buy">Is Buy?
              </label>
            </div>
            <div class="form-group">
              <input type="hidden" name="requestedPrice" value="1">
              <input type="hidden" name="uniqueId" value="<?=rand(1, 100000);?>">
              <input type="hidden" name="action" value="openMarket">
            </div>
            <button type="submit" class="btn btn-default">
              Submit
            </button>
          </form>
        </div>
        <div class="col-md-4">
          <table class="table table-condensed table-bordered">
            <thead>
            <tr>
              <th>
                #
              </th>
              <th>
                Product
              </th>
              <th>
                Payment Taken
              </th>
              <th>
                Status
              </th>
            </tr>
            </thead>
            <tbody>
            <tr>
              <td>
                1
              </td>
              <td>
                TB - Monthly
              </td>
              <td>
                01/04/2012
              </td>
              <td>
                Default
              </td>
            </tr>
            <tr class="active">
              <td>
                1
              </td>
              <td>
                TB - Monthly
              </td>
              <td>
                01/04/2012
              </td>
              <td>
                Approved
              </td>
            </tr>
            <tr class="success">
              <td>
                2
              </td>
              <td>
                TB - Monthly
              </td>
              <td>
                02/04/2012
              </td>
              <td>
                Declined
              </td>
            </tr>
            <tr class="warning">
              <td>
                3
              </td>
              <td>
                TB - Monthly
              </td>
              <td>
                03/04/2012
              </td>
              <td>
                Pending
              </td>
            </tr>
            <tr class="danger">
              <td>
                4
              </td>
              <td>
                TB - Monthly
              </td>
              <td>
                04/04/2012
              </td>
              <td>
                Call in to confirm
              </td>
            </tr>
            </tbody>
          </table>
        </div>
        <div class="col-md-4">
          <form role="form">
            <div class="form-group">

              <label for="exampleInputEmail1">
                Email address
              </label>
              <input type="email" class="form-control" id="exampleInputEmail1"
                     style="background-image: url(&quot;data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAASCAYAAABSO15qAAAAAXNSR0IArs4c6QAAAPhJREFUOBHlU70KgzAQPlMhEvoQTg6OPoOjT+JWOnRqkUKHgqWP4OQbOPokTk6OTkVULNSLVc62oJmbIdzd95NcuGjX2/3YVI/Ts+t0WLE2ut5xsQ0O+90F6UxFjAI8qNcEGONia08e6MNONYwCS7EQAizLmtGUDEzTBNd1fxsYhjEBnHPQNG3KKTYV34F8ec/zwHEciOMYyrIE3/ehKAqIoggo9inGXKmFXwbyBkmSQJqmUNe15IRhCG3byphitm1/eUzDM4qR0TTNjEixGdAnSi3keS5vSk2UDKqqgizLqB4YzvassiKhGtZ/jDMtLOnHz7TE+yf8BaDZXA509yeBAAAAAElFTkSuQmCC&quot;); background-repeat: no-repeat; background-attachment: scroll; background-size: 16px 18px; background-position: 98% 50%;"
                     autocomplete="off">
            </div>
            <div class="form-group">

              <label for="exampleInputPassword1">
                Password
              </label>
              <input type="password" class="form-control" id="exampleInputPassword1"
                     style="background-image: url(&quot;data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAASCAYAAABSO15qAAAAAXNSR0IArs4c6QAAAPhJREFUOBHlU70KgzAQPlMhEvoQTg6OPoOjT+JWOnRqkUKHgqWP4OQbOPokTk6OTkVULNSLVc62oJmbIdzd95NcuGjX2/3YVI/Ts+t0WLE2ut5xsQ0O+90F6UxFjAI8qNcEGONia08e6MNONYwCS7EQAizLmtGUDEzTBNd1fxsYhjEBnHPQNG3KKTYV34F8ec/zwHEciOMYyrIE3/ehKAqIoggo9inGXKmFXwbyBkmSQJqmUNe15IRhCG3byphitm1/eUzDM4qR0TTNjEixGdAnSi3keS5vSk2UDKqqgizLqB4YzvassiKhGtZ/jDMtLOnHz7TE+yf8BaDZXA509yeBAAAAAElFTkSuQmCC&quot;); background-repeat: no-repeat; background-attachment: scroll; background-size: 16px 18px; background-position: 98% 50%;"
                     autocomplete="off">
            </div>
            <div class="form-group">

              <label for="exampleInputFile">
                File input
              </label>
              <input type="file" id="exampleInputFile">
              <p class="help-block">
                Example block-level help text here.
              </p>
            </div>
            <div class="checkbox">

              <label>
                <input type="checkbox"> Check me out
              </label>
            </div>
            <button type="submit" class="btn btn-default">
              Submit
            </button>
          </form>
        </div>
      </div>
    </div>
    <div class="col-md-12">
      <div class="row">
        <p><?= $openMarketResult ?></p>
      </div>
    </div>
  </div>
</div>
</div>

<script src="../js/jquery.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/scripts.js"></script>
</body>
</html>