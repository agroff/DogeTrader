/******************************************
 * Begin API
 ******************************************/
doge.api = {};

doge.api.lastTime = 0;
doge.api.lastRefreshed = 0;

doge.api.getMethod = function (method, callback) {
    var url = doge.settings.api.url,
        data = {
            method : method
        };

    if (typeof method === 'object') {
        data = method;
    }

    $.ajax({
        url      : url,
        data     : data,
        dataType : "json",
        success  : function (data) {
            callback(data);
        },
        error    : function () {
            //silent failures will
            //be kept secret from the world
            //by this bad haiku
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

doge.api.clear = function () {
    doge.api.lastTime = 0;
    doge.orders.past.buys = [];
    doge.orders.past.sells = [];
    $("#currentPrice").html("...");
    $("#buyOrderList").html("");
    $("#sellOrderList").html("");
    $("#topTrades").html("");
};

doge.api.rates = function () {
    doge.api.getMethod("rates", function (data) {
        doge.convert.rates = data;
    });
};

doge.api.distributeResponse = function (response) {
    doge.api.lastTime = response.time;

    if(response.trades !== false){
        doge.lastPrice.render(response.trades);
        doge.trades.analyze(response.trades);
        doge.lastPrice.rendered = true;
    }

    if(response.sells !== false){
        doge.orders.render.sells(response.sells.orders);
    }

    if(response.buys !== false){
        doge.orders.render.buys(response.buys.orders);
    }
};

doge.api.ensureUpdates = function () {
    var now = new Date().getTime(),
        secondsSinceUpdate = (now - doge.api.lastRefreshed) / 1000;
    dbg("seconds since refresh: " + secondsSinceUpdate);

    if(secondsSinceUpdate > 28){
        dbg("Rebooting auto-update!");
        doge.api.refresh();
    }
};

doge.api.refresh = function () {
    var timer = 1000 * doge.settings.api.refresh_time,
        data = {
            time   : doge.api.lastTime,
            method : "all",
            market : doge.data.currentMarket
        };

    doge.api.getMethod(data, function (data) {

        doge.api.lastRefreshed =  new Date().getTime();

        doge.api.distributeResponse(data);
        setTimeout(function () {
            doge.api.refresh();
        }, timer)

    });
};