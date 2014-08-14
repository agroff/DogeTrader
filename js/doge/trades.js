
/******************************************
 * Recent Trades
 ******************************************/
doge.trades = {

    last: [],

    startDate : "",
    endDate   : "",

    buys : {
        count       : 0,
        btcTotal    : 0,
        dogeTotal   : 0,
        btcAverage  : 0,
        dogeAverage : 0
    },

    sells : {
        count       : 0,
        btcTotal    : 0,
        dogeTotal   : 0,
        btcAverage  : 0,
        dogeAverage : 0
    },

    emptyObject : function () {
        return {
            count       : 0,
            btcTotal    : 0,
            dogeTotal   : 0,
            btcAverage  : 0,
            dogeAverage : 0
        };
    },

    addTrade : function (trades, trade) {
        trades.count += 1;
        trades.btcTotal += trade.btc;
        trades.dogeTotal += trade.doge;

        return trades;
    },

    doAnalysis : function (transactionData) {
        var buys = doge.trades.emptyObject(),
            sells = doge.trades.emptyObject();

        $.each(transactionData, function (i, trade) {

            if (i == 0) {
                doge.trades.endDate = trade.time;
            }
            doge.trades.startDate = trade.time;

            trade = doge.utils.formatTrade(trade);

            if (trade.type === "Buy") {
                buys = doge.trades.addTrade(buys, trade);
            }
            else {
                sells = doge.trades.addTrade(sells, trade);
            }
        });

        buys.btcAverage = doge.utils.round(buys.btcTotal / buys.count, 3);
        buys.dogeAverage = doge.utils.round(buys.dogeTotal / buys.count, 0);

        sells.btcAverage = doge.utils.round(sells.btcTotal / buys.count, 3);
        sells.dogeAverage = doge.utils.round(sells.dogeTotal / buys.count, 0);

        doge.trades.buys = buys;
        doge.trades.sells = sells;
    },

    formatPercents : function (total, current) {
        var round = doge.utils.round,
            formatted,
            percent = doge.utils.percent(current, total);

        formatted = round(percent, 1) + "% <br />" + round(current, 2) + " BTC ";

        return formatted;

    },

    formatTradeStats : function (stats) {
        var round = doge.utils.round,
            formatNumber = doge.utils.formatNumber;

        stats.btcTotal = round(stats.btcTotal, 2);
        stats.dogeTotal = formatNumber(round(stats.dogeTotal, 0));
        stats.dogeAverage = formatNumber(stats.dogeAverage);

        return stats;
    },

    renderDate : function (id, date) {
        var utils = doge.utils,
            newDate = utils.minutesAgo(utils.fixCryptsyDate(date), true);
        $("#" + id).text(newDate);
    },

    renderTopTrades : function (transactionData) {
        var formatted = [];
        transactionData.sort(function (a, b) {
            return doge.utils.toNumber(b.total) - doge.utils.toNumber(a.total);
        });

        transactionData.splice(5, 200);


        $.each(transactionData, function (i, item) {
            formatted.push(doge.utils.formatTrade(item, true));
        });

        formatted.sort(function (a, b) {
            return b.date - a.date;
        });

        $("#topTrades").html("");
        $.each(formatted, function (i, item) {
            item.date = doge.utils.minutesAgo(item.date);

            $("#topTrades").loadTemplate($("#transactionTemplate"), item, {append : true});
        });
    },

    renderAnalysis : function (transactionData) {
        var $buy = $("#buyContainer"),
            $sell = $("#sellContainer"),
            buys = doge.trades.buys,
            sells = doge.trades.sells,
            buyTotal = doge.trades.buys.btcTotal,
            sellTotal = doge.trades.sells.btcTotal,
            total = buyTotal + sellTotal,
            buyPercent = doge.utils.percent(buyTotal, total),
            sellPercent = doge.utils.percent(sellTotal, total);



        $buy.html(doge.trades.formatPercents(total, buyTotal));
        $sell.html(doge.trades.formatPercents(total, sellTotal))

        if (buyPercent < 20) {
            buyPercent = 20;
            sellPercent = 80;
        }

        if (sellPercent < 20) {
            buyPercent = 80;
            sellPercent = 20;
        }

        $buy.css("width", buyPercent + "%");
        $sell.css("width", sellPercent + "%");

        buys.title = "Buys";
        buys = doge.trades.formatTradeStats(buys);


        sells.title = "Sells";
        sells = doge.trades.formatTradeStats(sells);

        $("#buyStats").loadTemplate($("#transactionStatsTemplate"), buys);
        $("#sellStats").loadTemplate($("#transactionStatsTemplate"), sells);

        doge.trades.renderDate("tradesStart", doge.trades.startDate);
        doge.trades.renderDate("tradesEnd", doge.trades.endDate);

        doge.trades.renderTopTrades(transactionData);
    },

    doTrimmedAnalysis: function(){
        var data = doge.trades.last.slice(0,doge.data.analyzeCount);

        doge.trades.doAnalysis(data);
        doge.trades.renderAnalysis(data);
    },

    analyze: function(transactionData){
        doge.trades.last = transactionData;
        doge.trades.doTrimmedAnalysis();
    },

    bind:function(){
        $("#analyzeCount").change(function(){
            doge.data.analyzeCount = $(this).val();
            doge.storeData();
            doge.trades.doTrimmedAnalysis();
        });
    }

};
