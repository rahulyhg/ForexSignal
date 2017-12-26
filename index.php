<?php
session_start();
if (!isset($_SESSION['isLogin']) || !$_SESSION['isLogin']) exit;
if (isset($_GET['day'])) {
  $_SESSION['day'] = $_GET['day'];
  header("Location: index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title id='Description'>Forex Signal Data</title>
  <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
  <link rel="icon" href="/favicon.ico" type="image/x-icon">
  <link rel="stylesheet" href="js/jqwidgets/styles/jqx.base.css" type="text/css"/>
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
  <meta name="viewport" content="width=device-width, initial-scale=1 maximum-scale=1 minimum-scale=1"/>
  <script type="text/javascript" src="js/scripts/jquery-1.11.1.min.js"></script>
  <script type="text/javascript" src="js/jqwidgets/jqxcore.js"></script>
  <script type="text/javascript" src="js/jqwidgets/jqxbuttons.js"></script>
  <script type="text/javascript" src="js/jqwidgets/jqxscrollbar.js"></script>
  <script type="text/javascript" src="js/jqwidgets/jqxmenu.js"></script>
  <script type="text/javascript" src="js/jqwidgets/jqxgrid.js"></script>
  <script type="text/javascript" src="js/jqwidgets/jqxgrid.sort.js"></script>
  <script type="text/javascript" src="js/jqwidgets/jqxgrid.filter.js"></script>
  <script type="text/javascript" src="js/jqwidgets/jqxdropdownlist.js"></script>
  <script type="text/javascript" src="js/jqwidgets/jqxgrid.selection.js"></script>
  <script type="text/javascript" src="js/jqwidgets/jqxgrid.columnsresize.js"></script>
  <script type="text/javascript" src="js/jqwidgets/jqxgrid.columnsreorder.js"></script>
  <script type="text/javascript" src="js/jqwidgets/jqxgrid.pager.js"></script>
  <script type="text/javascript" src="js/jqwidgets/jqxdata.js"></script>
  <script type="text/javascript" src="js/jqwidgets/jqxlistbox.js"></script>
  <script type="text/javascript" src="js/scripts/demos.js"></script>
  <script type="text/javascript">
      $(document).ready(function () {
          // Forex Signal
          var url = "data/4_AllIn1JSON.txt";
          var source =
              {
                  datatype: "json",
                  datafields: [
                      {name: 'currency', type: 'string'},
                      {name: 'dateTime', type: 'datetime'},
                      {name: 'stdLotds', type: 'string'},
                      {name: 'tradeType', type: 'string'},
                      {name: 'entryRate', type: 'string'},
                      {name: 'pipMultiplier', type: 'string'},
                      {name: 'currentPrice', type: 'string'},
                      {name: 'floatingPips', type: 'double'},
                      {name: 'trader', type: 'string'},
                      {name: 'traderId', type: 'string'},
                      {name: 'zuluAccountId', type: 'string'},
                      {name: 'rank', type: 'int'}
                  ],
                  id: 'id',
                  sortcolumn: "dateTime",
                  sortdirection: "desc",
                  url: url
              };
          var linkRenderer = function (row, datafield, value) {
              var traderId = this.owner.source.records[row]['traderId'];
              return '<a style="margin: auto;" target="_blank" href="https://www.zulutrade.com/trader/' + traderId + '">' +
                  '<img style="height: 30px;" src="https://www.zulutrade.com/webservices/Image.ashx?type=user&id=' + value + '"/>' +
                  '</a>';
          }
          var typeRenderer = function (row, datafield, value) {
              if (value == 'SELL') {
                  value = '<span style="color: red;">' + value + '</span>';
              } else {
                  value = '<span style="color: blue;">' + value + '</span>';
              }
              return '<div style="text-align: center; margin-top: 5px;">' + value + '</div>';
          }
          var floatingRenderer = function (row, datafield, value) {
              if (value < 0) {
                  value = '<span style="color: red;">' + value + '</span>';
              } else {
                  value = '<span style="color: blue;">' + value + '</span>';
              }
              return '<div style="text-align: center; margin-top: 5px;">' + value + '</div>';
          }
          var dataAdapter = new $.jqx.dataAdapter(source);
          $("#ForexSignalGrid").jqxGrid(
              {
                  width: 909,
                  height: 550,
                  autoheight: true,
                  source: dataAdapter,
                  sortable: true,
                  filterable: true,
                  columnsresize: true,
                  pageable: false,
                  columnsreorder: true,
                  showfilterrow: true,
                  pagesize: 15,
                  autorowheight: true,
                  selectionmode: 'multiplerows',
                  columns: [
                      {text: '#', datafield: 'rank', cellsalign: 'center', align: 'center', width: 30},
                      {
                          text: '#',
                          datafield: 'zuluAccountId',
                          key: true,
                          cellsrenderer: linkRenderer,
                          cellsalign: 'center',
                          align: 'center',
                          width: 29
                      },
                      {text: 'Trader', datafield: 'trader', cellsalign: 'center', align: 'center', width: 150},
                      {text: 'Currency', datafield: 'currency', cellsalign: 'center', align: 'center', width: 100},
                      {
                          text: 'Type',
                          datafield: 'tradeType',
                          cellsrenderer: typeRenderer,
                          cellsalign: 'center',
                          align: 'center',
                          width: 50
                      },
                      {text: 'Lot', datafield: 'stdLotds', cellsalign: 'center', align: 'center', width: 50},
                      {text: 'Date Open', datafield: 'dateTime', cellsalign: 'center', align: 'center', width: 200},
                      {text: 'Open', datafield: 'entryRate', cellsalign: 'center', align: 'center', width: 100},
                      {text: 'Current', datafield: 'currentPrice', cellsalign: 'center', align: 'center', width: 100},
                      {
                          text: 'Profit (pips)',
                          datafield: 'floatingPips',
                          cellsrenderer: floatingRenderer,
                          cellsalign: 'center',
                          align: 'center',
                          width: 100
                      }
                  ]
              });
          $("#ForexSignalGrid").on('rowselect', function (event) {
              console.log(event.args.row);
          });
          $("#ForexSignalGrid").on('rowunselect', function (event) {
              console.log(event.args.row);
          });
          var UpdateData = setInterval(function () {
              $("#ForexSignalGrid").jqxGrid('updatebounddata', 'cells');
          }, 10000);
          var CallApi = setInterval(function () {
            <?php
            $day = (isset($_SESSION['day'])) ? $_SESSION['day'] : 2;
            $url = "refreshJSON.php?day=$day";
            ?>
              $.ajax({url: "<?=$url?>"});
          }, 20000);

          // Forex Tip
          var url2 = "data/8_AllIn1JSON2.txt";
          var source2 =
              {
                  datatype: "json",
                  datafields: [
                      {name: 'currency', type: 'string'},
                      {name: 'stdLotds', type: 'string'},
                      {name: 'type', type: 'string'},
                      {name: 'entryRate', type: 'string'},
                      {name: 'currentPrice', type: 'string'},
                      {name: 'floatingPips', type: 'double'}
                  ],
                  id: 'id',
                  sortcolumn: "floatingPips",
                  sortdirection: "asc",
                  url: url2
              };

          var dataAdapter2 = new $.jqx.dataAdapter(source2);
          $("#ForexTipGrid").jqxGrid(
              {
                  width: 500,
                  height: 550,
                  autoheight: true,
                  source: dataAdapter2,
                  sortable: true,
                  filterable: true,
                  columnsresize: true,
                  pageable: false,
                  columnsreorder: true,
                  showfilterrow: true,
                  pagesize: 15,
                  autorowheight: true,
                  selectionmode: 'multiplerows',
                  columns: [
                      {text: 'Currency', datafield: 'currency', cellsalign: 'center', align: 'center', width: 100},
                      {
                          text: 'Type',
                          datafield: 'type',
                          cellsrenderer: typeRenderer,
                          cellsalign: 'center',
                          align: 'center',
                          width: 50
                      },
                      {text: 'Lot', datafield: 'stdLotds', cellsalign: 'center', align: 'center', width: 50},
                      {text: 'Open', datafield: 'entryRate', cellsalign: 'center', align: 'center', width: 100},
                      {text: 'Current', datafield: 'currentPrice', cellsalign: 'center', align: 'center', width: 100},
                      {
                          text: 'Profit (pips)',
                          datafield: 'floatingPips',
                          cellsrenderer: floatingRenderer,
                          cellsalign: 'center',
                          align: 'center',
                          width: 100
                      }
                  ]
              });
          $("#ForexTipGrid").on('rowselect', function (event) {
              console.log(event.args.row);
          });
          $("#ForexTipGrid").on('rowunselect', function (event) {
              console.log(event.args.row);
          });
          var UpdateData2 = setInterval(function () {
              $("#ForexTipGrid").jqxGrid('updatebounddata', 'cells');
          }, 10000);
      });
  </script>
</head>
<body class='default' background="image/background.jpg">
<div id="ForexTipGrid" style="margin: 20px; float: left;"></div>
<div id="ForexSignalGrid" style="margin: 20px; float: left;"></div>
</body>
</html>