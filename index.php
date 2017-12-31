<?php
session_start();
if (isset($_REQUEST['day'])) {
    $_SESSION['day'] = $_REQUEST['day'];
    header("location: index.php");
}
if (!isset($_SESSION['day'])) {
    $_SESSION['day'] = 2;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title id='Description'>Forex Signal Data</title>
  <link rel="shortcut icon" href="public/img/favicon.ico" type="image/x-icon">
  <link rel="icon" href="public/img/favicon.ico" type="image/x-icon">
  <link rel="stylesheet" href="public/js/jqwidgets/styles/jqx.base.css" type="text/css"/>
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
  <meta name="viewport" content="width=device-width, initial-scale=1 maximum-scale=1 minimum-scale=1"/>
  <link href="public/css/bootstrap.min.css" rel="stylesheet">
  <link href="public/css/style.css" rel="stylesheet">
  <link rel="manifest" href="/manifest.json"/>
  <script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script>
  <script>
    var OneSignal = window.OneSignal || [];
    OneSignal.push(function () {
      OneSignal.init({
        appId: "23e68763-a5e1-411a-8e79-1d4d09ad8b98",
        autoRegister: true,
        notifyButton: {
          enable: false
        },
        persistNotification: false
      });
    });
  </script>
  <script type="text/javascript" src="public/js/scripts/jquery-1.11.1.min.js"></script>
  <script type="text/javascript" src="public/js/jqwidgets/jqxcore.js"></script>
  <script type="text/javascript" src="public/js/jqwidgets/jqxbuttons.js"></script>
  <script type="text/javascript" src="public/js/jqwidgets/jqxscrollbar.js"></script>
  <script type="text/javascript" src="public/js/jqwidgets/jqxmenu.js"></script>
  <script type="text/javascript" src="public/js/jqwidgets/jqxgrid.js"></script>
  <script type="text/javascript" src="public/js/jqwidgets/jqxgrid.sort.js"></script>
  <script type="text/javascript" src="public/js/jqwidgets/jqxgrid.filter.js"></script>
  <script type="text/javascript" src="public/js/jqwidgets/jqxdropdownlist.js"></script>
  <script type="text/javascript" src="public/js/jqwidgets/jqxgrid.selection.js"></script>
  <script type="text/javascript" src="public/js/jqwidgets/jqxgrid.columnsresize.js"></script>
  <script type="text/javascript" src="public/js/jqwidgets/jqxgrid.columnsreorder.js"></script>
  <script type="text/javascript" src="public/js/jqwidgets/jqxgrid.pager.js"></script>
  <script type="text/javascript" src="public/js/jqwidgets/jqxdata.js"></script>
  <script type="text/javascript" src="public/js/jqwidgets/jqxlistbox.js"></script>
  <script type="text/javascript" src="public/js/scripts/demos.js"></script>
  <script type="text/javascript">
    $(document).ready(function () {
      // Forex Signal
      var url = "private/data/4_AllIn1JSON.txt";
      var source =
        {
          datatype: "json",
          datafields: [
            {name: 'currency', type: 'string'},
            {name: 'dateTime', type: 'string'},
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
      var dateRenderer = function (row, datafield, value) {
        var retVal = value.slice(0, 10);
        return '<div style="text-align: center; margin-top: 5px;">' + retVal + '</div>';
      }
      var dataAdapter = new $.jqx.dataAdapter(source);
      $("#ForexSignalGrid").jqxGrid(
        {
          width: 679,
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
            {
              text: 'Date Open',
              datafield: 'dateTime',
              cellsrenderer: dateRenderer,
              cellsalign: 'center',
              align: 'center',
              width: 120
            },
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
          $url = "private/api/refreshGridData.php?day={$_SESSION['day']}";
          ?>
        $.ajax({url: "<?=$url?>"});
      }, 20000);

      // Forex Tip
      var url2 = "private/data/8_AllIn1JSON2.txt";
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
      });
      $("#submit").off("click").on("click", function () {
        var data = $("form").serializeArray();
        var url = "private/api/getForexSignal.php";
        $("[name=addSignal]").val('');
        $.ajax({
          method: 'POST',
          url: url,
          data: data,
          success: function (response) {
            if (response) {
              var result = "Signal: " + response.replace(/,/g, ' - ');
              $("#result").html(result);
            }
          },
          error: function (response) {
            $("#result").html("ERROR!!!");
          }
        });
      });
      var UpdateData2 = setInterval(function () {
        $("#ForexTipGrid").jqxGrid('updatebounddata', 'cells');
      }, 10000);
      var UpdateData3 = setInterval(function () {
        var getselectedrowindexes = $("#ForexTipGrid").jqxGrid('getselectedrowindexes');
        var row = $("#ForexTipGrid").jqxGrid('getrowdata', getselectedrowindexes[0]);
        if (row) {
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
        }
      }, 5000);
      var UpdateForexSignal = setInterval(function () {
        var url = "private/data/forexSignal.txt";
        $.ajax({
          method: 'GET',
          url: url,
          success: function (response) {
            if (response) {
              var result = "Signal: " + response.replace(/,/g, ' - ');
              $("#result").html(result);
            }
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
  <div id="ForexSignalGrid" style="margin: 20px; float: left;"></div>
  <div id="addSignalForm" style="margin: 20px; float: left;">
    <form>
      <table>
        <tbody>
        <td style="max-width: 80px; text-align: center; padding-right: 5px;">
          <div class="form-group">
            <label for="type">Type</label>
            <input style="text-align: center;" type="text" class="form-control" id="type" name="type" readonly>
          </div>
        </td>
        <td style="max-width: 120px; text-align: center; padding-right: 5px;">
          <div class="form-group">
            <label for="symbol">Symbol</label>
            <input style="text-align: center;" type="text" class="form-control" id="symbol" name="symbol" readonly>
          </div>
        </td>
        <td style="max-width: 100px; text-align: center; padding-right: 5px;">
          <div class="form-group">
            <label for="price">Price</label>
            <input style="text-align: center;" type="text" class="form-control" id="price" name="price" readonly>
          </div>
        </td>
        <td style="max-width: 80px; text-align: center; padding-right: 5px;">
          <div class="form-group">
            <label for="addSignal">Password</label>
            <input style="text-align: center;" type="password" class="form-control" id="addSignal" name="addSignal">
          </div>
        </td>
        <td style="max-width: 80px; text-align: center; padding-left: 15px; padding-top: 10px">
          <button type="submit" class="btn btn-default" id="submit" onclick="return false;">No Action</button>
        </td>
        </tbody>
      </table>
      <input type="hidden" name="isDelete" value="false">
      <p class="help-block" id="result"></p>
    </form>
  </div>
</div>
<script src="public/js/bootstrap.min.js"></script>
<script src="public/js/scripts.js"></script>
</body>
</html>