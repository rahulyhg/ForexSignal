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
    echo($result);
  }

  function openMarket($dataArray)
  {
    $url = '/open/market';
    $this->cURL($url, $dataArray);
  }

  function updateLimit($dataArray)
  {
    $url = '/update/limit';
    $this->cURL($url, $dataArray);
  }

  function getOpen()
  {
    $url = 'getOpen';
    $this->cURL($url);
  }

  function getHistory($dataArray)
  {
    $url = 'getHistory/';
    $this->cURL($url, $dataArray);
  }

  function getProviderStatistics()
  {
    $url = 'getProviderStatistics/';
    $this->cURL($url);
  }

  function getTradingConfiguration()
  {
    $url = 'getTradingConfiguration/';
    $this->cURL($url);
  }

  function getInstruments()
  {
    $url = 'getInstruments/';
    $this->cURL($url);
  }
}

$zuluApi = new ZuluAPIs();
switch ($_REQUEST['action']) {
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
      'currencyName' => $_REQUEST['currencyName'],
      'lots' => $_REQUEST['lots'],
      'buy' => $_REQUEST['buy'],
      'requestedPrice' => $_REQUEST['requestedPrice'],
      'uniqueId' => $_REQUEST['uniqueId'],
    ];
    $zuluApi->openMarket($dataArray);
    break;
  case 'updateLimit':
    $dataArray = [
      'currencyName' => $_REQUEST['currencyName'],
      'buy' => $_REQUEST['buy'],
      'brokerTicket' => $_REQUEST['brokerTicket'],
      'limitValue' => $_REQUEST['limitValue']
    ];
    $zuluApi->updateLimit();
    break;
  case 'getInstruments':
    $zuluApi->getInstruments();
    break;
}