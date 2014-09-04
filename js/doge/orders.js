/****************************************
 * Order Book
 ****************************************/

doge.orders = {

    past : {
        buys  : [],
        sells : []
    },

    getTotalAtIndex : function (index, satoshi, isBuy) {
        var toNum = doge.utils.toNumber,
            orders;

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

        return toNum(orders[index][satoshi].total);
    },

    changeFromIndex : function (index, order, isBuy) {
        var toNum = doge.utils.toNumber,
            satoshi = order.satoshi,
            previousValue = doge.orders.getTotalAtIndex(index, satoshi, isBuy),
            change = toNum(order.total) - previousValue;

        if(previousValue === 0 && doge.orders.past.buys.length < 3){
            return;
        }

        if(change < 1){
            change = doge.utils.preciseRound(change, 3);
        }
        else {
            change = doge.utils.preciseRound(change, 2);
        }


        return change;
    },

    getOrderBookTotal : function(satoshi, isBuy){
        var index = 0;

        if (isBuy) {
            index = doge.orders.past.buys.length - 1;
        }
        else {
            index = doge.orders.past.sells.length - 1;
        }

        return doge.orders.getTotalAtIndex(index, satoshi, isBuy);
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
        if (log.length > 200) {
            log.shift();
        }
    },

    logMainChange : function (change) {
        doge.data.changeLog.push(change);

        if (doge.data.changeLog.length > doge.settings.orders.record_count) {
            doge.data.changeLog.shift();
        }
    },

    logChange : function (satoshi, btc, currentBtc, isBuy, isNotable) {
        var data = {
                satoshi : satoshi,
                btc     : Math.abs(btc),
                type    : "buys",
                total   : currentBtc,
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

        if(doge.utils.toNumber(data.btc) > 0.0011){
            doge.orders.logOrderBookChange(data);
        }
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

        if (Math.abs(change) > doge.settings.orders.change_threshold) {
            isNotable = true;
        }

        if(Math.abs(change) > 0){
            doge.orders.logChange(order.satoshi, change, order.total, isBuy, isNotable);
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
    },

    isCancelled : function(order, removals){
        var isCancelled = false;
        $.each(removals, function(i, item){
            if(order.btc === item.btc){
                //removals.splice(i, 1);
                isCancelled = true;
            }
        });

        return isCancelled;
    },

    satoshiPopup : function($row, type){
        var satoshi = $("div:first-child", $row).text(),
            msg = "<p class='light'>Order status is a rough estimate, only works if site is left open.</p>",
            log,
            $log,
            below = 0,
            current = 0,
            isBuy = false,
            removals = [],
            title = satoshi + " Satoshi Log";

        if(type === 'buys'){
            isBuy = true;
        }

        current = doge.orders.getOrderBookTotal(satoshi, isBuy);

        msg += "<div id=\"satoshiLog\" class=\"panel tablePanel\"></div>";
        doge.utils.error(msg, title);

        doge.orders.ensureOrderBookProperty(satoshi, type);

        log = doge.data.orderLog[type][satoshi].slice();

        log.reverse();


        $log = $("#satoshiLog");
        $.each(log, function (i, data) {
            var size = 'big',
                queue = 'Removal',
                above = doge.utils.preciseRound(current - below, 3),
                isCancelled = false;

            if (data.btc < 16) {
                size = 'medium'
            }
            if (data.btc < 6) {
                size = 'small'
            }

            if(data.english === 'added'){
                //dbg("before: " + removals.length);
                isCancelled = doge.orders.isCancelled(data, removals);
                //dbg("after : " + removals.length);
                queue = above + ' BTC Above Order';
                if(above < 0){
                    queue = 'Sold';
                }
            }
            else {
                removals.push(data);
            }

            if(isCancelled){
                queue = 'Cancelled';
            }

            data.queue = queue;


            data.class = "row smallTable " + data.english + " " + size;

            $log.loadTemplate($("#satoshiChangeTemplate"), data, {append : true});

            if(data.english === 'added' && !isCancelled){
                below += doge.utils.toNumber(data.btc);
            }
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


