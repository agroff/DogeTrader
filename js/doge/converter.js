/******************************************
 * Converter
 ******************************************/

doge.convert = {
    rates : {},

    getRate : function (currency) {
        var rates = doge.convert.rates[currency];
        if (rates == undefined) {
            return false;
        }
        if (rates["now"] != undefined) {
            return rates["now"];
        }
        if (rates["24h"] != undefined) {
            return rates["24h"];
        }
        if (rates["7d"] != undefined) {
            return rates["7d"];
        }
        if (rates["30d"] != undefined) {
            return rates["30d"];
        }

    },

    dogeToBtc : function (dogeCount) {
        var btcPerDoge = doge.utils.satoshiToBtc(doge.data.currentPrice),
            btc = btcPerDoge * dogeCount;

        return doge.utils.preciseRound(btc, 8);
    },

    btcToDoge : function (btc) {
        var btcPerDoge = doge.utils.satoshiToBtc(doge.data.currentPrice),
            dogeCount = btc / btcPerDoge;

        return doge.utils.preciseRound(dogeCount, 1);
    },

    btcToFiat : function (currency, btc) {
        var fiatRate = doge.convert.getRate(currency);
        return doge.utils.preciseRound(fiatRate * btc, 4);
    },

    fiatToBtc : function (currency, dollars) {
        var fiatRate = doge.convert.getRate(currency);
        return doge.utils.preciseRound(dollars / fiatRate, 8);
    },

    fromDoge : function () {
        var $doge = $("#convertDoge"),
            $btc = $("#convertBtc"),
            $usd = $("#convertUsd"),
            dogeCount = $doge.val(),
            btc = doge.convert.dogeToBtc(dogeCount),
            usd = doge.convert.btcToFiat("USD", btc);

        $btc.val(btc);
        $usd.val(usd);
        doge.convert.renderOtherCurrencies();
    },

    fromBtc : function () {
        var $doge = $("#convertDoge"),
            $btc = $("#convertBtc"),
            $usd = $("#convertUsd"),
            btc = $btc.val(),
            dogeCount = doge.convert.btcToDoge(btc),
            usd = doge.convert.btcToFiat("USD", btc);

        $doge.val(dogeCount);
        $usd.val(usd);
        doge.convert.renderOtherCurrencies();
    },

    fromUsd : function () {
        var $doge = $("#convertDoge"),
            $btc = $("#convertBtc"),
            $usd = $("#convertUsd"),
            usd = $usd.val(),
            btc = doge.convert.fiatToBtc("USD", usd),
            dogeCount = doge.convert.btcToDoge(btc);

        $btc.val(btc);
        $doge.val(dogeCount);
        doge.convert.renderOtherCurrencies();
    },

    doButton: function($input, $button){
        var val = $button.attr("data-value");
        $input.val(val);
        $input.trigger("keyup");
    },

    renderOtherCurrencies: function(){
        var others = doge.settings.converter.other_currencies,
            $container = $("#otherCurrencies"),
            btc = $("#convertBtc").val();

        $container.html("");
        $.each(others, function(i,item){
            var data = {
                currency : item,
                btc : btc,
                total : doge.convert.btcToFiat(item, btc)
            }
            $container.loadTemplate($("#currencyTemplate"), data, {append : true});
        });
    },

    bind : function () {
        $("#convertDoge").keyup(doge.convert.fromDoge);
        $("#convertBtc").keyup(doge.convert.fromBtc);
        $("#convertUsd").keyup(doge.convert.fromUsd);

        $("#convertBtc").change(function(){
            doge.convert.renderOtherCurrencies();
        });

        $(".usd-convert").click(function () {
            doge.convert.doButton($("#convertUsd"), $(this));
        })

        $(".btc-convert").click(function () {
            doge.convert.doButton($("#convertBtc"), $(this));
        })

        $(".doge-convert").click(function () {
            doge.convert.doButton($("#convertDoge"), $(this));
        });
    }
};
