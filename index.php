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
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">
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
          width: 750,
          height: 420,
          autoheight: false,
          source: dataAdapter,
          sortable: true,
          filterable: true,
          columnsresize: true,
          pageable: true,
          columnsreorder: true,
          showfilterrow: true,
          pagesize: 10,
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
//            {text: 'Trader', datafield: 'trader', cellsalign: 'center', align: 'center', width: 150},
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
          height: 420,
          autoheight: false,
          source: dataAdapter2,
          sortable: true,
          filterable: true,
          columnsresize: true,
          pageable: true,
          columnsreorder: true,
          showfilterrow: true,
          pagesize: 10,
          autorowheight: true,
          selectionmode: 'singlerow',
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
        var row = event.args.row;
        $("[name=type]").val(row['type']);
        $("[name=symbol]").val(row['currency']);
        $("[name=price]").val(row['currentPrice']);
        $("#submit").html(row['type']);
        $("#submit").removeClass('btn-default');
        if (row['type'] == 'BUY') {
          $("#submit").addClass('btn-success');
          $("#submit").removeClass('btn-danger');
        } else {
          $("#submit").addClass('btn-danger');
          $("#submit").removeClass('btn-success');
        }
        $("#submit").off("click").on("click", function () {
          var data = $("form").serializeArray();
          var url = "ForexSignal.php";
          $("[name=addSignal]").val('');
          $.ajax({
            method: 'POST',
            url: url,
            data: data,
            success: function (response) {
              var result = "NO SIGNAL!!!";
              if (response) {
                result = "Signal: " + response.replace(/,/g, '<br>');
              }
              $("#result").html(result);
            },
            error: function (response) {
              $("#result").html("ERROR!!!");
            }
          });
        });
      });
      var UpdateData2 = setInterval(function () {
        $("#ForexTipGrid").jqxGrid('updatebounddata', 'cells');
      }, 10000);
      var UpdateForexSignal = setInterval(function () {
        var url = "data/ForexSignal.txt";
        $.ajax({
          method: 'GET',
          url: url,
          success: function (response) {
            var result = "NO SIGNAL!!!";
            if (response) {
              result = "Signal: " + response.replace(/,/g, '<br>');
            }
            $("#result").html(result);
          },
          error: function (response) {
            $("#result").html("ERROR!!!");
          }
        });
      }, 3000);
    });
  </script>
</head>
<body class='default'>
<div style="max-width: 1475px">
  <div id="ForexTipGrid" style="margin: 20px; float: left;"></div>
  <div id="addSignalForm" style="margin: 20px; float: left; max-width: 100px">
    <form>
      <div class="form-group">
        <label for="type">Type</label>
        <input type="text" class="form-control" id="type" name="type" readonly>
      </div>
      <div class="form-group">
        <label for="symbol">Symbol</label>
        <input type="text" class="form-control" id="symbol" name="symbol" readonly>
      </div>
      <div class="form-group">
        <label for="price">Price</label>
        <input type="text" class="form-control" id="price" name="price" readonly>
      </div>
      <div class="form-group">
        <label for="addSignal">Check</label>
        <input type="password" class="form-control" id="addSignal" name="addSignal">
      </div>
      <button type="submit" class="btn btn-default" id="submit" onclick="return false;">No Action</button>
      <p class="help-block" id="result"></p>
    </form>
  </div>
  <div id="ForexSignalGrid" style="margin: 20px; float: left;"></div>
  <div class="row">
    <div class="col-md-12">
      <div class="panel-group" id="panel-754590">
        <div class="panel panel-default">
          <div class="panel-heading" style="padding-left: 50%;">
            <a class="panel-title collapsed" data-toggle="collapse" data-parent="#panel-754590"
               href="#panel-element-985306">Major Chart</a>
          </div>
          <div id="panel-element-985306" class="panel-collapse collapse">
            <div class="panel-body">
              <div id="Chart0" style="margin: 20px; float: left;"></div>
              <div id="Chart1" style="margin: 20px; float: left;"></div>
              <div id="Chart2" style="margin: 20px; float: left;"></div>
              <div id="Chart3" style="margin: 20px; float: left;"></div>
              <div id="Chart4" style="margin: 20px; float: left;"></div>
              <div id="Chart5" style="margin: 20px; float: left;"></div>
              <div id="Chart6" style="margin: 20px; float: left;"></div>
              <div id="Chart7" style="margin: 20px; float: left;"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript" src="https://s3.tradingview.com/tv.js"></script>
<script type="text/javascript" class="js-widget-demo-1">
  var majorSymbol = [
    "FX:EURUSD",
    "FX:GBPUSD",
    "FX:USDJPY",
    "FX:USDCHF",
    "FX:AUDUSD",
    "FX:USDCAD",
    "FX:EURGBP",
    "FX:EURCAD"
  ];

  majorSymbol.forEach(function (symbol, i) {
    var containerId = "Chart" + i;
    newChart(symbol, containerId);
  });

  function newChart(symbol, containerId) {
    new TradingView.widget({
      "container_id": containerId,
      "autosize": false,
      "width": 680,
      "height": 500,
      "symbol": symbol,
      "interval": "240",
      "timezone": "Asia/Bangkok",
      "theme": "Light",
      "style": "8",
      "locale": "en",
      "toolbar_bg": "#f1f3f6",
      "enable_publishing": false,
      "hide_top_toolbar": true,
      "save_image": false,
      "hideideas": true,
      "studies": [
        "BB@tv-basicstudies",
        "BollingerBandsWidth@tv-basicstudies"
      ]
    });
  }
</script>
<script src="js/bootstrap.min.js"></script>
<script src="js/scripts.js"></script>
</body>
</html>