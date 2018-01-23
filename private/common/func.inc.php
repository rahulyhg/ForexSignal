<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');
require_once('rest.inc.php');

function isRunning()
{
  $isRunning = file_get_contents(__DIR__ . '/../data/isRunning.txt');
  if ($isRunning) {
    exit('Running');
  }
  file_put_contents(__DIR__ . '/../data/isRunning.txt', true);
}

function getTop20Id()
{
  $url = 'https://www.zulutrade.com/zulutrade-client/v2/api/providers/performance/search';
  $dataJson = '{"timeFrame":10000,"minPips":0.1,"tradingExotics":false,"minWeeks":36,"minWinTradesPercentage":80,"sortBy":"amountFollowing","sortAsc":false,"size":20,"page":0,"flavor":"global"}';
  $top20Obj = RestCurl::post($url, $dataJson);
  $top20Data = $top20Obj['data']->result;
  $top20IdArray = [];
  foreach ($top20Data as $d) {
    $tmp['id'] = ($d->trader->providerId);
    $tmp['name'] = ($d->trader->profile->name);
    $tmp['zuluAccountId'] = ($d->trader->profile->zuluAccountId);
    $top20IdArray[] = $tmp;
  }

  file_put_contents(__DIR__ . '/../data/0_Top20ID.txt', json_encode($top20IdArray));
}

function getDataFromId()
{
  $top20ID = json_decode(file_get_contents(__DIR__ . '/../data/0_Top20ID.txt'), true);
  $returnArray = [];
  foreach ($top20ID as $index => $id) {
    $url = 'https://www.zulutrade.com/zulutrade-client/traders/api/providers/' . $id['id'] . '/openTrades';
    $top20Obj = RestCurl::get($url);
    $top20Data = $top20Obj['data'];
    if (!$top20Obj['data']) continue;
    foreach ($top20Data as $data) {
      $day = (isset($_REQUEST['day'])) ? $_REQUEST['day'] : 2;
      if (((time() * 1000) - $data->dateTime) > ($day * 24 * 60 * 60 * 1000)) continue;
      if (!$data->performanceValid) continue;
      @$tmp['currency'] = str_replace('/', '', $data->currencyName);
      @$tmp['dateTime'] = date('Y-m-d H:i:s', ($data->dateTime / 1000));
      @$tmp['stdLotds'] = 1;
//      @$tmp['stdLotds'] = $data->stdLotds;
      @$tmp['tradeType'] = $data->tradeType;
      @$tmp['entryRate'] = $data->entryRate;
      @$tmp['pipMultiplier'] = $data->pipMultiplier;
      $returnArray[$id['name'] . '/' . $id['id'] . '/' . $id['zuluAccountId']][] = $tmp;
    }
  }

  file_put_contents(__DIR__ . '/../data/1_Top20Data.txt', json_encode($returnArray));
}

function getCurrentPrice()
{
  $listCurrency = json_decode(file_get_contents(__DIR__ . '/../common/1ForgeListCurrency.txt'), true);
  $listCurrency = implode(',', $listCurrency);
  $apiKey = [];
  $apiKey[] = 'Jf3MVdUSbaHCVy1Tn9EFMAUdkosZhbJj';
//  $apiKey[] = 'oaKms1onINj6VoYXYGKYgAUdYYKDnhyA';
//  $apiKey[] = 'ClrMRVp0f9n1tqDHyvhxYerwU1U248UZ';
//  $apiKey[] = 'aGacuvy68EAezgTPio45GGdn0b0e0sit';
//  $apiKey[] = '1lJcTFrdrR7IWW4YWsWUovGq1gtE72Jl';
//  $apiKey[] = 'a5iuTDnIXCyM9VSmKsRJfofOC1Hnlo0S';
//  $apiKey[] = 'tbjwLNRUXWWTtplPqXgiNNdElXPRr15Y';
//  $apiKey[] = 'eBnQGyYnBlSgCZXqUx52EANWfbOnrrbz';
//  $apiKey[] = 'OIXsKdMvzL4E38v101txYz9GBbKqZQ25';
//  $apiKey[] = 'pyJlh4mq3p6SwG2Oo7EAcgVEb9muLU8s';
//  $index = rand(0, 9);
  $index = 0;
  $priceObj = RestCurl::get('https://forex.1forge.com/1.0.3/quotes?pairs=' . $listCurrency . '&api_key=' . $apiKey[$index]);
  $returnArray = [];
  foreach ($priceObj['data'] as $d) {
    $tmp['price'] = $d->price;
    $tmp['bid'] = $d->bid;
    $tmp['ask'] = $d->ask;
    $returnArray[$d->symbol] = $tmp;
  }

  file_put_contents(__DIR__ . '/../data/2_CurrentPrice.txt', json_encode($returnArray));
}

function getFloatingPips()
{
  $top20Data = json_decode(file_get_contents(__DIR__ . '/../data/1_Top20Data.txt'), true);
  $currentPriceArray = json_decode(file_get_contents(__DIR__ . '/../data/2_CurrentPrice.txt'), true);
  $returnArray = [];
  foreach ($top20Data as $trader => $tradeArray) {
    foreach ($tradeArray as $i => $trade) {
      $currentPrice = $currentPriceArray[$trade['currency']]['price'];
      $floatingPips = 0;
      if ($trade['tradeType'] == 'BUY') {
        $floatingPips = $currentPrice - $trade['entryRate'];
      } elseif ($trade['tradeType'] == 'SELL') {
        $floatingPips = $trade['entryRate'] - $currentPrice;
      }
      $floatingPips = $floatingPips * $trade['pipMultiplier'];
      $digits = ($trade['pipMultiplier'] > 1000) ? 5 : 3;
      $trade['entryRate'] = number_format($trade['entryRate'], $digits);
      $trade['currentPrice'] = number_format($currentPrice, $digits);
      $trade['floatingPips'] = (float)number_format($floatingPips, 2);
      $returnArray[$trader][$i] = $trade;
    }
  }

  file_put_contents(__DIR__ . '/../data/3_Top20FloatingPips.txt', json_encode($returnArray));
}

function allIn1JSON()
{
  $top20FloatingPips = json_decode(file_get_contents(__DIR__ . '/../data/3_Top20FloatingPips.txt'), true);
  $returnArray = [];
  $i = 0;
  foreach ($top20FloatingPips as $trader => $tradeArray) {
    $i++;
    foreach ($tradeArray as $trade) {
      $tmp = explode('/', $trader);
      $trade['rank'] = $i;
      $trade['trader'] = $tmp[0];
      $trade['traderId'] = $tmp[1];
      $trade['zuluAccountId'] = $tmp[2];
      $returnArray[] = $trade;
    }
  }

  file_put_contents(__DIR__ . '/../data/4_AllIn1JSON.txt', json_encode($returnArray));
}

function formatDataByCurrency()
{
  $top20Data = json_decode(file_get_contents(__DIR__ . '/../data/1_Top20Data.txt'), true);
  $returnArray = [];
  foreach ($top20Data as $trader => $tradeData) {
    foreach ($tradeData as $index => $data) {
      $currency = str_replace('/', '', $data['currency']);
      $tmp = [];
      $tmp['stdLotds'] = $data['stdLotds'];
      $tmp['entryRate'] = $data['entryRate'];
      $tmp['pipMultiplier'] = $data['pipMultiplier'];
      $returnArray[$currency][$data['tradeType']][] = $tmp;
    }
  }
  file_put_contents(__DIR__ . '/../data/5_FormatDataByCurrency.txt', json_encode($returnArray));
}

function zipDataByCurrency()
{
  $formatDataByCurrency = json_decode(file_get_contents(__DIR__ . '/../data/5_FormatDataByCurrency.txt'), true);
  $currentPrice = json_decode(file_get_contents(__DIR__ . '/../data/2_CurrentPrice.txt'), true);
  $returnArray = [];
  foreach ($formatDataByCurrency as $currency => $dataObj) {
    foreach ($dataObj as $type => $data) {
      $totalLotByType = 0;
      $totalPriceByType = 0;
      $pipMultiplier = 0;
      foreach ($data as $order) {
        $totalLotByType += $order['stdLotds'];
        $totalPriceByType += ($order['stdLotds'] * $order['entryRate']);
        $pipMultiplier = $order['pipMultiplier'];
      }
      // if ($totalLotByType < 1) continue;
      $averagePrice = ($totalPriceByType / $totalLotByType);
      $averagePrice = number_format($averagePrice, strlen($pipMultiplier));
      $floatingPips = ($currentPrice[$currency]['price'] - $averagePrice) * $pipMultiplier;
      $floatingPips = ($type == 'BUY') ? $floatingPips : $floatingPips * -1;
      $floatingPips = (float)number_format($floatingPips, 2);
      $tmp['currency'] = $currency;
      $tmp['tradeType'] = $type;
      $tmp['stdLotds'] = (float)number_format($totalLotByType, 2);
      $tmp['entryRate'] = (float)$averagePrice;
      $tmp['currentPrice'] = $currentPrice[$currency]['price'];
      $tmp['floatingPips'] = $floatingPips;
      $returnArray[$currency][$type] = $tmp;
    }
  }

  file_put_contents(__DIR__ . '/../data/6_ZipDataByCurrency.txt', json_encode($returnArray));
}

function getTip()
{
  $zipDataByCurrency = json_decode(file_get_contents(__DIR__ . '/../data/6_ZipDataByCurrency.txt'), true);
  $tmp = [];
  foreach ($zipDataByCurrency as $currency => $dataObj) {
    foreach ($dataObj as $type => $data) {
      if (!$tmp || ($tmp['floatingPips'] > $data['floatingPips'])) {
        if ($data['stdLotds'] <= 1) continue;
        $tmp['currency'] = $currency;
        $tmp['tradeType'] = $type;
        $tmp['stdLotds'] = $data['stdLotds'];
        $tmp['entryRate'] = $data['entryRate'];
        $tmp['currentPrice'] = $data['currentPrice'];
        $tmp['floatingPips'] = $data['floatingPips'];
      }
    }
  }
  file_put_contents(__DIR__ . '/../data/7_GetTip.txt', json_encode($tmp));
}

function allIn1JSON2()
{
  $zipDataByCurrency = json_decode(file_get_contents(__DIR__ . '/../data/6_ZipDataByCurrency.txt'), true);
  $returnArray = [];
  foreach ($zipDataByCurrency as $currency => $dataObj) {
    foreach ($dataObj as $type => $order) {
      $tmp = [];
      $tmp['currency'] = $currency;
      $tmp['type'] = $type;
      $tmp['stdLotds'] = $order['stdLotds'];
      $tmp['entryRate'] = $order['entryRate'];
      $tmp['currentPrice'] = $order['currentPrice'];
      $tmp['floatingPips'] = $order['floatingPips'];
      $returnArray[] = $tmp;
    }
  }

  file_put_contents(__DIR__ . '/../data/8_AllIn1JSON2.txt', json_encode($returnArray));
}

function done()
{
  file_put_contents(__DIR__ . '/../data/isRunning.txt', false);
  exit('Done');
}