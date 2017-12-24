<?php
function isRunning()
{
  $isRunning = file_get_contents('data/isRunning.txt');
  if ($isRunning) {
    exit('Running');
  }
  file_put_contents('data/isRunning.txt', true);
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

  file_put_contents('data/0_Top20ID.txt', json_encode($top20IdArray));
}

function getDataFromId()
{
  $top20ID = json_decode(file_get_contents('data/0_Top20ID.txt'), true);
  $returnArray = [];
  foreach ($top20ID as $index => $id) {
    $url = 'https://www.zulutrade.com/zulutrade-client/traders/api/providers/' . $id['id'] . '/openTrades';
    $top20Obj = RestCurl::get($url);
    $top20Data = $top20Obj['data'];
    if (!$top20Obj['data']) continue;
    foreach ($top20Data as $data) {
      $day = 7;
      if (((time() * 1000) - $data->dateTime) > ($day * 24 * 60 * 60 * 1000)) continue;
      if (!$data->performanceValid) continue;
      @$tmp['currency'] = str_replace('/', '', $data->currencyName);
      @$tmp['dateTime'] = date('Y-m-d H:i:s', ($data->dateTime / 1000));
      @$tmp['stdLotds'] = $data->stdLotds;
      @$tmp['tradeType'] = $data->tradeType;
      @$tmp['entryRate'] = $data->entryRate;
      @$tmp['pipMultiplier'] = $data->pipMultiplier;
      $returnArray[$id['name'] . '/' . $id['id'] . '/' . $id['zuluAccountId']][] = $tmp;
    }
  }

  file_put_contents('data/1_Top20Data.txt', json_encode($returnArray));
}

function getCurrentPrice()
{
  $listCurrency = json_decode(file_get_contents('1ForgeListCurrency.txt'), true);
  $listCurrency = implode(',', $listCurrency);
  $apiKey = [];
  $apiKey[] = 'oaKms1onINj6VoYXYGKYgAUdYYKDnhyA';
  $apiKey[] = 'Jf3MVdUSbaHCVy1Tn9EFMAUdkosZhbJj';
  $apiKey[] = 'ClrMRVp0f9n1tqDHyvhxYerwU1U248UZ';
  $apiKey[] = 'aGacuvy68EAezgTPio45GGdn0b0e0sit';
  $apiKey[] = '1lJcTFrdrR7IWW4YWsWUovGq1gtE72Jl';
  $apiKey[] = 'a5iuTDnIXCyM9VSmKsRJfofOC1Hnlo0S';
  $apiKey[] = 'tbjwLNRUXWWTtplPqXgiNNdElXPRr15Y';
  $apiKey[] = 'eBnQGyYnBlSgCZXqUx52EANWfbOnrrbz';
  $apiKey[] = 'OIXsKdMvzL4E38v101txYz9GBbKqZQ25';
  $apiKey[] = 'pyJlh4mq3p6SwG2Oo7EAcgVEb9muLU8s';
  $index = rand(0, 9);
  $priceObj = RestCurl::get('https://forex.1forge.com/1.0.2/quotes?pairs=' . $listCurrency . '&api_key=' . $apiKey[$index]);
  $returnArray = [];
  foreach ($priceObj['data'] as $d) {
    $tmp['price'] = $d->price;
    $tmp['bid'] = $d->bid;
    $tmp['ask'] = $d->ask;
    $returnArray[$d->symbol] = $tmp;
  }

  file_put_contents('data/2_CurrentPrice.txt', json_encode($returnArray));
}

function getFloatingPips()
{
  $top20Data = json_decode(file_get_contents('data/1_Top20Data.txt'), true);
  $currentPriceArray = json_decode(file_get_contents('data/2_CurrentPrice.txt'), true);
  $returnArray = [];
  foreach ($top20Data as $trader => $tradeArray) {
    foreach ($tradeArray as $i => $trade) {
      $currentPrice = $currentPriceArray[$trade['currency']]['price'];
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

  file_put_contents('data/3_Top20FloatingPips.txt', json_encode($returnArray));
}

function allIn1JSON()
{
  $top20FloatingPips = json_decode(file_get_contents('data/3_Top20FloatingPips.txt'), true);
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

  file_put_contents('data/4_AllIn1JSON.txt', json_encode($returnArray));
}

function done()
{
  file_put_contents('data/isRunning.txt', false);
  exit('Done');
}

isRunning();
getTop20Id();
getDataFromId();
getCurrentPrice();
getFloatingPips();
allIn1JSON();
done();