/****************************************
 * Order Book
 ****************************************/

doge.orders = {

    past : {
        buys  : [],
        sells : []
    },

    changeFromIndex: function(index, order, isBuy){
        var toNum = doge.utils.toNumber,
            satoshi = order.satoshi,
            orders, change;

        if(isBuy){
            orders = doge.orders.past.buys;
        }
        else {
            orders = doge.orders.past.sells;
        }

        if(orders[index] == undefined){
            return 0;
        }
        if(orders[index][satoshi] == undefined){
            return 0;
        }

        change = toNum(order.total) - toNum(orders[index][satoshi].total);

        change = doge.utils.preciseRound(change, 1);

        return change;
    },

    getChange: function(order, isBuy){
        var clss = "red",
            change = doge.orders.changeFromIndex(0, order, isBuy);;

        if(change > 0) {
            clss = "green";
            change = "+"+change;
        }
        if(change == 0){
            change = "--";
            clss = "";
        }

        return '<span class="'+clss+'">'+change+'</span>';
    },

    logChange: function(satoshi, btc){
        var $log = $("#changelog"),
            data = {
                satoshi: satoshi,
                btc: btc,
                date: doge.utils.minutesAgo(new Date(), false)
            };

        dbg(satoshi);
        dbg(btc);
        dbg("woo");
        data.english = "removed from";
        if(btc > 0){
            data.english = "added to";
        }


        $log.loadTemplate($("#changeTemplate"), data, {prepend: true});

    },

    logNotableChange: function(order, isBuy){
        var change, index;

        if(isBuy){
            index = doge.orders.past.buys.length - 1;
        }
        else {
            index = doge.orders.past.buys.length - 1;
        }

        change = doge.orders.changeFromIndex(index, order, isBuy);;

        if(Math.abs(change) >= doge.settings.orders.change_threshold) {
            doge.orders.logChange(order.satoshi, change);
        }
    },

    populateOrderBook : function (data, containerId) {
        var orderBook = $("#" + containerId),
            options = {append : true},
            isBuy = false;

        if(containerId == "buyOrderList") {
            isBuy = true;
            options = {prepend: true};
        }

        orderBook.html("");
        $.each(data, function (i, item) {

            item.change = doge.orders.getChange(item, isBuy);
            doge.orders.logNotableChange(item, isBuy);
            orderBook.loadTemplate($("#orderTemplate"), item, options);
        })
    },

    formatOrderList : function (data) {
        var formatted = {},
            utils = doge.utils,
            cost = 0;

        $.each(data, function (i, item) {
            var total = utils.preciseRound(item[2], 3),
                satoshi = utils.btcToSatoshi(item[0]);

            cost += utils.toNumber(total);

            formatted[satoshi] = {
                satoshi : satoshi,
                doge    : utils.formatManyDoge(item[1]),
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


