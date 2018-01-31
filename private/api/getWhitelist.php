<?php
require '../common/htmlDom.php';

$html = file_get_html('https://icodrops.com/whitelist/');
$result = [];
$closed = [];
foreach ($html->find('div.whtico-row') as $element) {
  @$tmp['name'] = $element->find("div.white_info h3", 0)->plaintext;
  @$tmp['type'] = $element->find("span.white-ico-category-name", 0)->plaintext;
  @$tmp['require'] = $element->find("div.whitelist_meta_icon", 0)->plaintext;
  @$tmp['status'] = $element->find("div.whitelist_date", 0)->plaintext;
  @$tmp['link'] = $element->find("div#white_join a", 0)->href;
  if (strpos($tmp['status'], "Closed") === false) {
    $result[$tmp['name']] = $tmp;
  } else {
    $closed[$tmp['name']] = $tmp;
  }
}
ksort($result);
ksort($closed);
print "<table>";
foreach ($result as $ico) {
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
foreach ($closed as $ico) {
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
