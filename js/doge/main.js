

doge.storeData = function(){
    localStorage["dogeData"] = JSON.stringify(doge.data);
};

doge.loadData = function(){
    var loaded;
    if(localStorage["dogeData"] != undefined){
        loaded = JSON.parse(localStorage["dogeData"]);
        $.extend(doge.data, loaded);
    }
};

doge.main = function () {
    var settingsUrl = '/settings.json';

    $.get(settingsUrl, function (settings) {
        doge.settings = settings;
        doge.settings.coin = settings.coins[$("body").attr("data-coin")]
        doge.common();
        doge.controller();
    }, "json");

    $("#moon-button").click(function () {
        doge.alarm.celebrate();
    });
};

doge.initialRequestFinished = function(){

    $("#buySat").val(doge.data.currentPrice);
    $("#sellSat").val(doge.data.currentPrice + 1);
    if(doge.data.buys.length === 0){
        $("#addBuy").trigger("click");
    }
    else {
        doge.calc.renderBuys();
    }

    $("#sellSat").trigger("keyup");
    $("#convertDoge").trigger("keyup");
};

doge.setMarket = function(){
    $("#currentMarket").text(doge.data.currentMarket);
}

doge.applyLoadedData = function(){
    doge.alarm.render();
    $("#analyzeCount").val(doge.data.analyzeCount);
    doge.orders.renderLog();
    doge.setMarket();
};

doge.initialRequest = function() {

    var data = {
        method : "all",
        market : doge.data.currentMarket
    };
    doge.api.getMethod(data, function (data) {
        doge.api.distributeResponse(data);
        doge.initialRequestFinished();

        setTimeout(function(){
            doge.api.refresh();
        }, 3000)

    });

};

doge.common = function(){
    $("#donateButton").click(function(){
        var msg = "",
            title = "Donations Always Appreciated!",
            coin = doge.utils.getCoin();

        msg += "Donation address can be copied below. Send lots of " + coin + " and watch the total donation count increase. ";
        msg += "It may take up to an hour to show your contribution. <br /><br /> "
        msg += "Thanks a bunch!<br /><br />"
        msg += "Address Here: <br />"
        msg += "<textarea id=\"donationTextArea\">"
        msg += doge.settings.coin.donation_address;
        msg += "</textarea>"
        doge.utils.error(msg, title);
        setTimeout(function(){
            $("#donationTextArea").select();
        }, 1000)

    });

    $("a", "#selectMarket").click(function(){
        var market = $.trim($(this).text());
        doge.data.currentMarket = market;
        doge.storeData();
        doge.setMarket();
        doge.api.clear();
        doge.api.refresh();
    });
}

doge.settingsLoaded = function () {

    doge.convert.bind();
    doge.calc.bind();
    doge.alarm.bind();
    doge.graph.fetch();
    doge.trades.bind();
    doge.api.rates();

    doge.loadData();
    doge.applyLoadedData();
    doge.initialRequest();

};



doge.controller = function(){
    var uri = window.location.pathname.substr(1);
    switch (uri){
        case "arbitrage":
            doge.arbitrage.bind();
            break;
        default:
            doge.settingsLoaded();
            break;
    }
};




//hi
