

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
        doge.settingsLoaded();
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

doge.applyLoadedData = function(){
    doge.alarm.render();
    $("#analyzeCount").val(doge.data.analyzeCount);
    doge.orders.renderLog();

};

doge.initialRequest = function() {

    doge.api.getMethod("all", function (data) {
        doge.api.distributeResponse(data);
        doge.initialRequestFinished();

        setTimeout(function(){
            doge.api.refresh();
        }, 3000)

    });

};

doge.settingsLoaded = function () {
    doge.initialRequest();
    doge.convert.bind();
    doge.calc.bind();
    doge.alarm.bind();
    doge.graph.fetch();
    doge.trades.bind();
    doge.api.rates();

    doge.loadData();
    doge.applyLoadedData();

    $("#donateButton").click(function(){
        var msg = "",
            title = "Donations. Much Appreciate.";

        msg += "Donation address can be copied below. Send lots of doge and watch the total donation count increase. ";
        msg += "It may take up to an hour to show your contribution. <br /><br /> "
        msg += "Wow. Such fun. So rich. Very thanks<br /><br />"
        msg += "Address Here: <br />"
        msg += "<textarea id=\"donationTextArea\">"
        msg += doge.settings.site.donation_address;
        msg += "</textarea>"
        doge.utils.error(msg, title);
        setTimeout(function(){
            $("#donationTextArea").select();
        }, 1000)

    });
};