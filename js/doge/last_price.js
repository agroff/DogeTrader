
/******************************************
 * Last Price
 ******************************************/
doge.lastPrice = {

    rendered : false,

    events : [],

    calculate : function (transactions) {
        var averageAmount = doge.settings.price.average_count,
            total = 0,
            satoshi, btc;
        for (var i = 0; i < averageAmount; i++) {
            btc = transactions[i].price;
            satoshi = doge.utils.btcToSatoshi(btc)
            total += satoshi;
        }

        return doge.utils.round(total / averageAmount, 1);
    },

    trigger: function(newPrice){
        $.each(doge.lastPrice.events, function(i, item){
            item(newPrice);
        })
    },

    bindNewPrice: function(callback){
        doge.lastPrice.events.push(callback);
    },

    get: function(){
        return doge.data.currentPrice;
    },

    render : function (transactions) {
        var utils = doge.utils,
            lastPrice = doge.lastPrice.calculate(transactions),
            coin = utils.ucfirst(utils.getCoin());

        doge.data.currentPrice = lastPrice;

        document.title = lastPrice + " Sat. "+coin+"Trader";

        this.trigger(lastPrice);

        $("#currentPrice").html(lastPrice);
    }

//    fetch : function () {
//        doge.api.last(function (transactionData) {
//
//        });
//
//        setTimeout(function () {
//            doge.lastPrice.fetch();
//        }, 4000)
//    }
};
