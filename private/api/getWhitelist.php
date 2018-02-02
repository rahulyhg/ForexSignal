<?php
require '../common/htmlDom.php';

$html = file_get_html('https://icodrops.com/whitelist/');
$currentWhitelist = [];
$closedWhitelist = [];
foreach ($html->find('div.whtico-row') as $element) {
  @$tmp['name'] = $element->find("div.white_info h3", 0)->plaintext;
  @$tmp['type'] = $element->find("span.white-ico-category-name", 0)->plaintext;
  @$tmp['require'] = $element->find("div.whitelist_meta_icon", 0)->plaintext;
  @$tmp['status'] = $element->find("div.whitelist_date", 0)->plaintext;
  @$tmp['link'] = $element->find("div#white_join a", 0)->href;
  if (strpos($tmp['status'], "Closed") === false) {
    $currentWhitelist[$tmp['name']] = $tmp;
  } else {
    $closedWhitelist[$tmp['name']] = $tmp;
  }
}
ksort($currentWhitelist);
ksort($closedWhitelist);
$html = file_get_html('http://icowhitelists.com/files/telegram-tracker.html');
$tmp = [];
$telegramMember = [];
foreach ($html->find('tbody tr') as $element) {
  @$tmp['name'] = $element->find("td", 0)->plaintext;
  @$tmp['member'] = $element->find("td", 2)->plaintext;
  @$tmp['growth'] = $element->find("td", 3)->plaintext;
  @$tmp['link'] = $element->find("td a", 0)->href;
  if ($tmp['link']) $telegramMember[] = $tmp;
}
print "<table>";
foreach ($currentWhitelist as $ico) {
  print "<tr>";
  print "<td>";
  print $ico['name'];
  print "</td>";
  print "<td>";
  print $ico['type'];
  print "</td>";
  print "<td>";
  print $ico['require'];
  print "</td>";
  print "<td>";
  print $ico['status'];
  print "</td>";
  print "<td>";
  print $ico['link'];
  print "</td>";
  print "</tr>";
}
print "</table>";
print "<table>";
foreach ($closedWhitelist as $ico) {
  print "<tr>";
  print "<td>";
  print $ico['name'];
  print "</td>";
  print "<td>";
  print $ico['type'];
  print "</td>";
  print "<td>";
  print $ico['require'];
  print "</td>";
  print "<td>";
  print $ico['status'];
  print "</td>";
  print "<td>";
  print $ico['link'];
  print "</td>";
  print "</tr>";
}
print "</table>";
print "<table>";
foreach ($telegramMember as $ico) {
  print "<tr>";
  print "<td>";
  print $ico['name'];
  print "</td>";
  print "<td>";
  print $ico['member'];
  print "</td>";
  print "<td>";
  print $ico['growth'];
  print "</td>";
  print "<td>";
  print $ico['link'];
  print "</td>";
  print "</tr>";
}
print "</table>";
