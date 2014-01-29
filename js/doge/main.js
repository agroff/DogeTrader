function dbg(variable) {
    console.log(variable);
}

doge = {};

doge.data = {
    currentPrice : "",
    buys         : [],
    alarms       : []
}

/*
 * Populated by /settings.json
 * structure is there for autocomplete and reference.
 */
doge.settings = {
    api   : {},
    price : {
        average_count : ""
    }
};

doge.utils = {
    btcToSatoshi : function (btc) {
        return Math.round(btc * 100000000);
    },

    satoshiToBtc : function (satoshi) {
        return (satoshi / 100000000);
    },

    round : function (num, decimals) {
        return Math.round(num * Math.pow(10, decimals)) / Math.pow(10, decimals);
    },

    toNumber : function (str) {
        return parseFloat(str);
    },

    percent : function (value, total) {
        return (value / total) * 100
    },

    percentOf : function (value, percent) {
        var multiplier = percent / 100;
        return value * multiplier;
    },

    minutesAgo : function (date, hideSeconds) {
        var d = new Date(),
            diff = d - date,
            seconds = diff / 1000,
            h = date.getHours(),
            hours = h,
            s = date.getSeconds(),
            m = date.getMinutes(),
            am = "am",
            hideSeconds = hideSeconds || false;

        if (s < 10) {
            s = "0" + s;
        }
        if (m < 10) {
            m = "0" + m;
        }
        if (hours > 12) {
            am = "pm";
            hours -= 12;
        }
        else if (hours === 0) {
            hours = 12;
        }

        if (hideSeconds) {
            s = "";
        }
        else {
            s = ":" + s
        }

        //return (Math.floor(seconds/60))+'m '+Math.round(seconds%60)+'s'
        return hours + ':' + m + s + " " + am;
    },

    fixCryptsyDate : function (cryptsyDate) {
        var d = new Date(cryptsyDate),
            offset = d.getTimezoneOffset() / 60;
        d.setHours(d.getHours() + 5 - offset);
        return d;
        //return "<br />" + d.toTimeString() + " <br /> " + cryptsyDate;
    },

    formatTrade : function (trade, display) {
        var utils = doge.utils,
            num = utils.toNumber,
            toSat = utils.btcToSatoshi,
            round = utils.round,
            display = display || false,
            formatted = {
                date    : trade[0],
                type    : trade[1],
                satoshi : toSat(trade[2]),
                doge    : num(trade[3]),
                btc     : num(trade[4])
            };

        if (display) {
            formatted.btc = round(formatted.btc, 3)
            formatted.date = utils.fixCryptsyDate(formatted.date);
            //            formatted.date = utils.fixCryptsyDate(formatted.date);
        }


        return formatted;
    },

    formatNumber : function (number) {
        return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
};

/******************************************
 * Begin API
 ******************************************/
doge.api = {};

doge.api.getMethod = function (method, callback) {
    var url = doge.settings.api.url,
        data = {
            method : method
        };

    $.ajax({
        url      : url,
        data     : data,
        dataType : "json",
        success  : function (data) {
            callback(data);
        },
        error    : function () {
            // in this case an error response simply means don't update the ui.
            // it may be caused by a serverside error or simply an indication that the data hasn't changed.
        }
    });
}

doge.api.last = function (callback) {
    doge.api.getMethod("last", function (data) {
        if (data.cached != undefined && doge.lastPrice.rendered === true) {
            return;
        }
        callback(data.aaData);
    });
};

doge.api.rates = function () {
    doge.api.getMethod("rates", function (data) {
        doge.convert.rates = data;
    });
};

doge.alarm = {};

doge.alarm.celebrate = function () {
    var rocket = $("#rocket"),
        top = $(window).height(),
        body = $("body"),
        song = $('<iframe width="0" height="0" src="//www.youtube-nocookie.com/embed/21LA888yC48?autoplay=1" frameborder="0" allowfullscreen></iframe>');

    $(".celebrate").show();
    $("#moon-button").hide();
    body.addClass("space");

    rocket.css({
        top  : top,
        left : 0
    });

    setTimeout(function () {
        rocket.animate({
            top  : 80,
            left : $(window).width() - 120
        }, 40000);
    }, 2000)

    setTimeout(function () {
        song.remove();
        $(".celebrate").hide();
        body.removeClass("space")
        $("#moon-button").show();
    }, 50000)

    body.append(song);
};

/******************************************
 * Calculator
 ******************************************/

doge.calc = {

    totalDoge: 0,
    totalBtc: 0,

    renderSellReport : function (sellSatoshi) {
        var utils = doge.utils,
            sellReport = {};

        sellReport.spent = doge.calc.totalBtc;

        sellReport.earned = utils.satoshiToBtc(sellSatoshi) * doge.calc.totalDoge;
        sellReport.earned = sellReport.earned - utils.percentOf(sellReport.earned, 0.3);
        sellReport.earned = utils.round(sellReport.earned, 8);

        sellReport.gained = sellReport.earned - sellReport.spent;
        sellReport.gainPercent = utils.percent(sellReport.gained, sellReport.spent);

        sellReport.gained = utils.round(sellReport.gained, 6)
        sellReport.gainPercent = utils.round(sellReport.gainPercent, 2)

        $("#sellReport").loadTemplate($("#sellReportTemplate"), sellReport);
    },

    renderBuys : function () {
        var num = doge.utils.toNumber,
            $buyList = $("#buyList"),
            totalDoge = 0,
            totalBtc = 0;

        $buyList.html("");
        $.each(doge.data.buys, function(i, item){
            $buyList.loadTemplate($("#buyTemplate"), item, {append : true});
            totalDoge += num(item.doge);
            totalBtc += num(item.totalCost);
        })

        totalBtc = doge.utils.round(totalBtc, 8);

        doge.calc.totalBtc = totalBtc;
        doge.calc.totalDoge = totalDoge;

        $("#dogeBought").text(doge.utils.formatNumber(totalDoge));
        $("#buyDogeTotal").text(totalDoge);
        $("#buyBtcTotal").text(totalBtc);
    },

    getBuy : function () {
        var buy = {
            doge    : $("#buyDoge").val(),
            satoshi : $("#buySat").val()
        }

        buy.cost = buy.doge * doge.utils.satoshiToBtc(buy.satoshi);

        buy.totalCost = doge.utils.round( buy.cost + doge.utils.percentOf(buy.cost, 0.2), 8);

        buy.id = new Date().getTime();

        return buy;
    },

    removeBuy : function(id){
        var newBuys = [];
        $.each(doge.data.buys, function(i, item){
            if(item.id != id){
                newBuys.push(item);
            }
        });

        doge.data.buys = newBuys;
        doge.calc.renderBuys();
    },

    addBuy : function () {
        var buy = doge.calc.getBuy();

        doge.data.buys.push(buy);

        doge.calc.renderBuys();
    },

    bind : function () {
        $("#addBuy").click(doge.calc.addBuy);

        $( "#buyList" ).on( "click", ".unbuy", function() {
            var id = $(".buyId", $(this).parent().parent()).text();
            doge.calc.removeBuy(id);
        });

        $("#sellSat").keyup(function(){
            var sellSatoshi = $(this).val();
            doge.calc.renderSellReport(sellSatoshi);
        });
    }
}
/******************************************
 * Converter
 ******************************************/

doge.convert = {
    rates : {},

    getRate : function (currency) {
        var rates = doge.convert.rates[currency];
        return rates["24h"];
    },

    do : function () {
        var $doge = $("#convertDoge"),
            $btc = $("#convertBtc"),
            $usd = $("#convertUsd"),
            rate = doge.convert.getRate("USD"),
            dogeCount = $doge.val(),
            btc = doge.utils.satoshiToBtc(doge.data.currentPrice) * dogeCount,
            usd = rate * btc;

        btc = doge.utils.round(btc, 9)
        usd = doge.utils.round(usd, 4)
        $btc.val(btc);
        $usd.val(usd);
    },

    bind : function () {
        $("#doConversion").click(doge.convert.do);
    }
};

/******************************************
 * Recent Trades
 ******************************************/
doge.trades = {

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
                doge.trades.endDate = trade[0];
            }
            doge.trades.startDate = trade[0];

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
            return doge.utils.toNumber(b[4]) - doge.utils.toNumber(a[4]);
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

        doge.trades.doAnalysis(transactionData);

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
    }

};


/******************************************
 * Last Price
 ******************************************/
doge.lastPrice = {

    rendered : false,

    calculate : function (transactions) {
        var averageAmount = doge.settings.price.average_count,
            total = 0,
            satoshi, btc;
        for (var i = 0; i < averageAmount; i++) {
            btc = transactions[i][2];
            satoshi = doge.utils.btcToSatoshi(btc)
            total += satoshi;
        }

        return doge.utils.round(total / averageAmount, 1);
    },

    render : function (transactions) {
        var lastPrice = doge.lastPrice.calculate(transactions);

        doge.data.currentPrice = lastPrice;

        $("#currentPrice").html(lastPrice);
    },

    fetch : function () {
        doge.api.last(function (transactionData) {
            doge.lastPrice.render(transactionData);
            doge.trades.renderAnalysis(transactionData);
            doge.lastPrice.rendered = true;
        });

        setTimeout(function () {
            doge.lastPrice.fetch();
        }, 4000)
    }
};


doge.main = function () {
    var settingsUrl = '/settings.json';

    $.get(settingsUrl, function (settings) {
        doge.settings = settings;
        doge.settingsLoaded();
    }, "json");

    $("#moon-button").click(function () {
        doge.alarm.celebrate();
    });
};

doge.settingsLoaded = function () {
    doge.lastPrice.fetch();
    doge.convert.bind();
    doge.calc.bind();
    doge.api.rates();
};