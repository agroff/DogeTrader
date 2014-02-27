/******************************************
 * Calculator
 ******************************************/

doge.calc = {

    totalDoge : 0,
    totalBtc  : 0,

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
        sellReport.dogeGain = utils.round(doge.convert.btcToDoge(sellReport.gained), 1);
        sellReport.usdGain = utils.round(doge.convert.btcToFiat("USD", sellReport.gained), 4)

        $("#sellReport").loadTemplate($("#sellReportTemplate"), sellReport);
    },

    renderBuys : function () {
        var num = doge.utils.toNumber,
            $buyList = $("#buyList"),
            totalDoge = 0,
            totalBtc = 0;

        $buyList.html("");
        $.each(doge.data.buys, function (i, item) {
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

        buy.totalCost = doge.utils.round(buy.cost + doge.utils.percentOf(buy.cost, 0.2), 8);

        buy.id = doge.utils.time();

        return buy;
    },

    removeBuy : function (id) {
        var newBuys = [];
        $.each(doge.data.buys, function (i, item) {
            if (item.id != id) {
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

    try : function () {
        var num = doge.utils.toNumber,
            dogeCount = num($("#buyDoge").val()),
            sat = num($("#buySat").val()),
            sellSat = num($("#sellSat").val());

        if(dogeCount === false || sat === false || sellSat === false) {
            return;
        }

        if(doge.data.buys.length == 0){
            doge.calc.addBuy();
            doge.calc.renderSellReport(sellSat);
            doge.data.buys = [];
            doge.calc.renderBuys();
        }
        else {

        }

    },

    bind : function () {
        $("#addBuy").click(doge.calc.addBuy);

        $("#buyList").on("click", ".unbuy", function () {
            var id = $(".buyId", $(this).parent().parent()).text();
            doge.calc.removeBuy(id);
        });

        $("#buySat").keyup(doge.calc.try);
        $("#buyDoge").keyup(doge.calc.try);
        $("#sellSat").keyup(function () {
            var sellSatoshi = $(this).val();
            doge.calc.renderSellReport(sellSatoshi);
        });
    }
};