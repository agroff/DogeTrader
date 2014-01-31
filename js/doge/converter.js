

/******************************************
 * Converter
 ******************************************/

doge.convert = {
    rates : {},

    getRate : function (currency) {
        var rates = doge.convert.rates[currency];
        if(rates["24h"] != undefined){
            return rates["24h"];
        }
        if(rates["24h"] != undefined){
            return rates["24h"];
        }
        if(rates["7d"] != undefined){
            return rates["7d"];
        }
        if(rates["30d"] != undefined){
            return rates["30d"];
        }

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
