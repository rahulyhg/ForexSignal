<!DOCTYPE html>
<html lang="en">
  <head>
    <title id='Description'>Forex Signal Data</title>
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="js/jqwidgets/styles/jqx.base.css" type="text/css" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1 maximum-scale=1 minimum-scale=1" />
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
          var url = "data/4_AllIn1JSON.txt";
          // prepare the data
          var source =
          {
              datatype: "json",
              datafields: [
                  { name: 'currency', type: 'string' },
                  { name: 'dateTime', type: 'datetime' },
                  { name: 'stdLotds', type: 'string' },
                  { name: 'tradeType', type: 'string' },
                  { name: 'entryRate', type: 'string' },
                  { name: 'pipMultiplier', type: 'string' },
                  { name: 'currentPrice', type: 'string' },
                  { name: 'floatingPips', type: 'double' },
                  { name: 'trader', type: 'string' },
                  { name: 'traderId', type: 'string' }
              ],
              id: 'id',
              url: url
          };
          var linkRenderer = function (row, datafield, value) {
              return '<a style="margin: auto;" target="_blank" href="http://www.zulutrade.com/trader/' + value + '"><img style="height: 30px;" src="http://www.zulutrade.com/webservices/Image.ashx?type=user&size=XL&ignore=false&id=' + value + '"/></a>';
            }
          var typeRenderer = function (row, datafield, value) {
            if (value == 'SELL') {
              value = '<span style="color: red;">' + value + '</span>';
            } else {
              value = '<span style="color: blue;">' + value + '</span>';
            }
              return value;
            }
          var dataAdapter = new $.jqx.dataAdapter(source);
          $("#grid").jqxGrid(
          {
              width: 1000,
              height: 550,
              autoheight: false,
              source: dataAdapter,
              sortable: true,
              filterable: true,
              columnsresize: true,
              pageable: true,
              columnsreorder: true,
              showfilterrow: true,
              pagesize: 15,
              autorowheight: true,
              selectionmode: 'singlerow',
              columns: [
                { text: '#', datafield: 'traderId', key: true, cellsrenderer: linkRenderer, width: 30},
                { text: 'Trader', datafield: 'trader', width: 220},
                { text: 'Currency', datafield: 'currency', width: 100},
                { text: 'Type', datafield: 'tradeType', cellsrenderer: typeRenderer, width: 50},
                { text: 'Lot', datafield: 'stdLotds', width: 50},
                { text: 'Date Open', datafield: 'dateTime', width: 250},
                { text: 'Open', datafield: 'entryRate', width: 100},
                { text: 'Current', datafield: 'currentPrice', width: 100},
                { text: 'Profit (pips)', datafield: 'floatingPips', width: 100}
            ]
          });
        var UpdateData = setInterval(function () {
              $("#grid").jqxGrid('updatebounddata', 'cells');
          }, 10000);
        var CallApi = setInterval(function () {
              $.ajax({url: "api.php"});
          }, 20000);
      });
    </script>
  </head>
  <body class='default' background="image/background.jpg">
    <div id="grid" style="margin: auto"></div>
  </body>
</html>