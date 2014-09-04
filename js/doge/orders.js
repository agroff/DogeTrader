/****************************************
 * Order Book
 ****************************************/

doge.orders = {

    past : {
        buys  : [],
        sells : []
    },

    changeFromIndex : function (index, order, isBuy) {
        var toNum = doge.utils.toNumber,
            satoshi = order.satoshi,
            orders, change;

        if (isBuy) {
            orders = doge.orders.past.buys;
        }
        else {
            orders = doge.orders.past.sells;
        }

        if (orders[index] == undefined) {
            return 0;
        }
        if (orders[index][satoshi] == undefined) {
            return 0;
        }

        change = toNum(order.total) - toNum(orders[index][satoshi].total);

        if(change < 1){
            change = doge.utils.preciseRound(change, 3);
        }
        else {
            change = doge.utils.preciseRound(change, 2);
        }


        return change;
    },

    getChange : function (order, isBuy) {
        var clss = "red",
            change = doge.orders.changeFromIndex(0, order, isBuy),
            number = doge.utils.toNumber(change);

        if (number > 0) {
            clss = "green";
            change = "+" + change;
        }
        if (number == 0) {
            change = "--";
            clss = "";
        }

        return '<span class="' + clss + '">' + change + '</span>';
    },

    renderLog : function () {
        var $log = $("#changelog");

        $log.html("");
        $("#totalLogs").text(doge.data.changeLog.length);
        $.each(doge.data.changeLog, function (i, data) {
            var size = 'big';
            if (data.btc < 16) {
                size = 'medium'
            }
            if (data.btc < 6) {
                size = 'small'
            }

            data.class = "row smallTable " + data.english + " " + size;

            $log.loadTemplate($("#changeTemplate"), data, {prepend : true});
        });
    },

    ensureOrderBookProperty : function (satoshi, type) {
        if(!doge.data.orderLog){
            doge.data.orderLog = {
                buys : {},
                sells : {}
            };
        }
        if(!doge.data.orderLog[type]){
            doge.data.orderLog[type] = {};
        }
        if(!doge.data.orderLog[type][satoshi]){
            doge.data.orderLog[type][satoshi] = [];
        }
    },

    logOrderBookChange : function (change) {
        var log;
        doge.orders.ensureOrderBookProperty(change.satoshi, change.type);

        log = doge.data.orderLog[change.type][change.satoshi];

        log.push(change);
        if (log.length > 50) {
            log.shift();
        }
    },

    logMainChange : function (change) {
        doge.data.changeLog.push(change);

        if (doge.data.changeLog.length > doge.settings.orders.record_count) {
            doge.data.changeLog.shift();
        }
    },

    logChange : function (satoshi, btc, isBuy, isNotable) {
        var data = {
                satoshi : satoshi,
                btc     : Math.abs(btc),
                type    : "buys",
                date    : doge.utils.minutesAgo(new Date(), false)
            };

        if(!isBuy){
            data.type = "sells";
        }

        data.english = "removed";
        if (btc > 0) {
            data.english = "added";
        }

        if(data.btc < 1){
            data.btc = '.' + data.btc.toString().split(".")[1];
        }

        doge.orders.logOrderBookChange(data);
        if(isNotable){
            doge.orders.logMainChange(data)
        }
    },

    logNotableChange : function (order, isBuy) {
        var isNotable = false,
            change, index;

        if (isBuy) {
            index = doge.orders.past.buys.length - 1;
        }
        else {
            index = doge.orders.past.sells.length - 1;
        }

        change = doge.orders.changeFromIndex(index, order, isBuy);

        dbg(doge.settings.orders.change_threshold);
        if (Math.abs(change) > doge.settings.orders.change_threshold) {
            isNotable = true;
        }

        if(Math.abs(change) > 0){
            doge.orders.logChange(order.satoshi, change, isBuy, isNotable);
        }
    },

    populateOrderBook : function (data, containerId) {
        var orderBook = $("#" + containerId),
            options = {append : true},
            isBuy = false;

        if (containerId == "buyOrderList") {
            isBuy = true;
            options = {prepend : true};
        }

        orderBook.html("");
        $.each(data, function (i, item) {

            item.change = doge.orders.getChange(item, isBuy);
            doge.orders.logNotableChange(item, isBuy);
            orderBook.loadTemplate($("#orderTemplate"), item, options);
        });


        doge.storeData();

        doge.orders.renderLog();

        dbg(doge.data.orderLog);
    },

    satoshiPopup : function($row, type){
        var satoshi = $("div:first-child", $row).text(),
            msg = "",
            log,
            $log,
            title = satoshi + " Satoshi Log";

        msg += "<div id=\"satoshiLog\" class=\"panel tablePanel\"></div>";
        doge.utils.error(msg, title);

        doge.orders.ensureOrderBookProperty(satoshi, type);

        log = doge.data.orderLog[type][satoshi];


        $log = $("#satoshiLog");
        $.each(log, function (i, data) {
            var size = 'big';
            if (data.btc < 16) {
                size = 'medium'
            }
            if (data.btc < 6) {
                size = 'small'
            }

            data.class = "row smallTable " + data.english + " " + size;

            $log.loadTemplate($("#satoshiChangeTemplate"), data, {prepend : true});
        });

        if(log.length === 0){
            $log.html('<h5>No changes.</h5>')
        }


    },

    formatOrderList : function (data) {
        var formatted = {},
            utils = doge.utils,
            cost = 0;

        $.each(data, function (i, item) {
            var total = utils.preciseRound(item.btc, 3),
                satoshi = utils.btcToSatoshi(item.price);

            cost += utils.toNumber(total);

            formatted[satoshi] = {
                satoshi : satoshi,
                doge    : utils.formatManyDoge(item.count),
                total   : total,
                cost    : utils.preciseRound(cost, 1)
            };
        });

        return formatted;
    },

    storeOrderList : function (data, type) {
        var past = doge.orders.past;

        past[type].push(data);

        if (past[type].length > 10) {
            past[type].shift();
        }
    },

    bind: function(){
        $("#buyOrderList").on("click", '.smallTable', function(){
            doge.orders.satoshiPopup($(this), "buys");
        });
        $("#sellOrderList").on("click", '.smallTable', function(){
            doge.orders.satoshiPopup($(this), "sells");
        });
    },

    render : {
        buys : function (buys) {
            buys = doge.orders.formatOrderList(buys);
            doge.orders.populateOrderBook(buys, "buyOrderList")
            doge.orders.storeOrderList(buys, "buys")
        },

        sells : function (sells) {
            sells = doge.orders.formatOrderList(sells);
            doge.orders.populateOrderBook(sells, "sellOrderList");
            doge.orders.storeOrderList(sells, "sells")
        }
    }

};


