doge.utils = {
    btcToSatoshi : function (btc) {
        return Math.round(btc * 100000000);
    },

    satoshiToBtc : function (satoshi) {
        return (satoshi / 100000000);
    },

    preciseRound : function (num, decimals) {
        var sign = num >= 0 ? 1 : -1;
        return (Math.round((num * Math.pow(10, decimals)) + (sign * 0.001)) / Math.pow(10, decimals)).toFixed(decimals);
    },

    round : function (num, decimals) {
        return Math.round(num * Math.pow(10, decimals)) / Math.pow(10, decimals);
    },

    toNumber : function (str) {
        var number = parseFloat(str);

        if (isNaN(number)) {
            return false;
        }

        return number;
    },

    time : function () {
        return new Date().getTime();
    },

    percent : function (value, total) {
        return (value / total) * 100
    },

    percentOf : function (value, percent) {
        var multiplier = percent / 100;
        return value * multiplier;
    },

    error : function (message, title) {
        var t = title || 'Wow. Such Error. Very Problem.';
        $("#errorContent").html(message);
        $("#errorTitle").html(t);
        $('#errorModal').foundation('reveal', 'open');
    },

    minutesAgo : function (date, hideSeconds) {
        var d = new Date(),
            diff = d - date,
            seconds = diff / 1000,
            h = date.getHours(),
            hours = h,
            s = date.getSeconds(),
            m = date.getMinutes(),
            am = "am",
            hideSeconds = hideSeconds || false;

        if (s < 10) {
            s = "0" + s;
        }
        if (m < 10) {
            m = "0" + m;
        }
        if (hours > 12) {
            am = "pm";
            hours -= 12;
        }
        else if (hours === 0) {
            hours = 12;
        }

        if (hideSeconds) {
            s = "";
        }
        else {
            s = ":" + s
        }

        //return (Math.floor(seconds/60))+'m '+Math.round(seconds%60)+'s'
        return hours + ':' + m + s + " " + am;
    },

    fixDate: function(d, hours){
        var offset = d.getTimezoneOffset() / 60,
            hours = hours || 4;
        d.setHours(d.getHours() + hours - offset);
        return d;
    },

    fixCryptsyDate : function (cryptsyDate) {
        dbg(cryptsyDate)
        var d = new Date(cryptsyDate * 1000);

        return doge.utils.fixDate(d);
        //return "<br />" + d.toTimeString() + " <br /> " + cryptsyDate;
    },

    formatManyDoge : function (doge) {
        if (doge < 100000) {
            return this.formatNumber(Math.round(doge));
        }

        return this.round(doge / 1000000, 3) + " M";
    },

    formatTrade : function (trade, display) {
        var utils = doge.utils,
            num = utils.toNumber,
            toSat = utils.btcToSatoshi,
            round = utils.round,
            display = display || false,
            formatted = {
                date    : trade.time,
                type    : trade.type,
                satoshi : toSat(trade.price),
                doge    : num(trade.amount),
                btc     : num(trade.total)
            };

        if (display) {
            formatted.btc = round(formatted.btc, 3)
            formatted.date = utils.fixCryptsyDate(formatted.date);
            //            formatted.date = utils.fixCryptsyDate(formatted.date);
        }


        return formatted;
    },

    formatNumber : function (number) {
        return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
};
