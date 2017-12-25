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

  function openMarket($currencyName = "EUR/USD", $lots = 0.1, $buy = false, $requestedPrice = 1.12)
  {
    $dataArray = [
      'currencyName' => $currencyName,
      'lots' => $lots,
      'buy' => $buy,
      'requestedPrice' => $requestedPrice,
      'uniqueId' => '1234567',
    ];
    $url = '/open/market';
    $this->cURL($url, $dataArray);
  }

  function updateLimit($currencyName = "EUR/USD", $buy = false, $brokerTicket = "123543", $limitValue = 1.5)
  {
    $dataArray = [
      'currencyName' => $currencyName,
      'buy' => $buy,
      'brokerTicket' => $brokerTicket,
      'limitValue' => $limitValue
    ];
    $url = '/update/limit';
    $this->cURL($url, $dataArray);
  }

  function getOpen()
  {
    $url = 'getOpen';
    $this->cURL($url);
  }

  function getHistory($startDate = '2015-03-29', $endDate = '2015-04-01')
  {
    $dataArray = [
      'startDate' => $startDate,
      'endDate' => $endDate
    ];
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
//$zuluApi->getOpen();
//$zuluApi->getHistory();
//$zuluApi->getProviderStatistics();
//$zuluApi->getTradingConfiguration();
//$zuluApi->getInstruments();
$zuluApi->openMarket();
$zuluApi->updateLimit();