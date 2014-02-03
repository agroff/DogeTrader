

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
    doge.trades.bind();
    doge.api.rates();

    doge.loadData();
    doge.applyLoadedData();
};