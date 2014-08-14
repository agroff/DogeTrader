doge.arbitrage = {

    bind: function () {
        $(".feasibilityButton").click(function () {
            var row = $(this).parent().parent(),
                container = $(".report", row),
                data = {
                    method: "checkTrade",
                    currency: row.attr("data-coin"),
                    buy: row.attr("data-buyAt"),
                    sell: row.attr("data-sellAt")
                };

            container.html("Calculating...");
            doge.api.getMethod(data, function (data) {
                doge.arbitrage.showReport(data, container);
                if (data.percent_gain < 0) {
                    row.hide();
                }
            });
        });

        var count = 0;
        $(".feasibilityButton").each(function () {
            var that = $(this);

            if(count < 30)
            {
                setTimeout(function(){
                    that.trigger("click");
                }, count * 800)

                count++;
            }
        });
    },

    showReport: function (data, container) {
        container.loadTemplate($("#arbitrageReport"), data);
    }

};