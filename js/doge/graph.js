/**
 * Created by andy on 2/11/14.
 */
doge.graph = {

    textY : 20,

    bind : function () {
        $("#graphPeriod").change(doge.graph.update);
        $(".graphToggle").click(function(){
            var bttn = $(this),
                clss = doge.graph.getButtonPathClass(bttn);

            if(bttn.hasClass("secondary")){
                bttn.removeClass("secondary");
            }
            else {
                bttn.addClass("secondary");
            }

            doge.graph.applyButtonState(bttn, clss);
        });
    },

    getButtonPathClass: function(bttn){
        var clss = "coinbase";

        if(bttn.hasClass("vos")){
            clss = "vos";
        }
        if(bttn.hasClass("cryptsy")){
            clss = "cryptsy";
        }

        return clss;
    },

    applyButtonState: function(bttn, pathClass){

        if(bttn.hasClass("secondary")){
            d3.select("path."+pathClass).classed("faded", true);
        }
        else {
            d3.select("path."+pathClass).classed("faded", false);
        }

    },

    fetch : function () {
        doge.graph.bind();
        doge.graph.doUpdates();
    },

    doUpdates: function(){
        var ms = 5 * 60 * 1000;

        doge.graph.update();

        setTimeout(doge.graph.doUpdates, ms);
    },

    renderButtonStates: function(){
        $(".graphToggle").each(function(){
            var bttn = $(this),
                clss = doge.graph.getButtonPathClass(bttn);

            doge.graph.applyButtonState(bttn, clss);
        });
    },

    update : function () {
        var days = $("#graphPeriod").val();
        $("#graph").html("<h3 >Loading...<h3>");

        doge.api.getMethod({method : "graph", days : days}, function (data) {
            $("#graph").html("");
            doge.graph.textY = 20;
            doge.graph.render(data);
            doge.graph.renderButtonStates();
        })
    },


    getLine : function (data, xScale, yScale) {

        // create a line function that can convert data[] into x and y points
        var line = d3.svg.line()
//            .interpolate("basis")
            .interpolate("cardinal")
            // assign the X function to plot our line as we wish
            .x(function (d, i) {
                // verbose logging to show what's actually being done
                // return the X coordinate where we want to plot this datapoint
                return xScale(d.time);
            })
            .y(function (d) {
                // verbose logging to show what's actually being done
                // return the Y coordinate where we want to plot this datapoint
                return yScale(d.value);
            });

        return line;
    },

    getDomain : function (dataSet) {
        var domain = [], val;
        val = d3.min(dataSet, function (c) {
            return c.value;
        });

        domain.push(val - 5);

        val = d3.max(dataSet, function (c) {
            return c.value;
        });

        //lovely to int hack
        domain.push((val - 0) + 5);


        return domain;
    },

    mergeDomains : function (one, two) {
        var domainOne = doge.graph.getDomain(one),
            domainTwo = doge.graph.getDomain(two),
            newDomain = [];
        newDomain[0] = domainOne[0];
        newDomain[1] = domainOne[1];

        if (domainTwo[0] < domainOne[0]) {
            newDomain[0] = domainTwo[0];
        }
        if (domainTwo[1] > domainOne[1]) {
            newDomain[1] = domainTwo[1];
        }

        return newDomain;
    },

    fixDate : function (data, parseDate) {
        $.each(data, function (i, item) {
            var time = parseDate(item.time);
            time = doge.utils.fixDate(time, 8);
            data[i]["time"] = time;
        });

        return data;
    },

    addText : function (rect, clss) {
        var txt,
            g;

        g = rect.append("g")
            .attr("y", doge.graph.textY)

        g.append("circle")
            .attr("r", 3.5)
            .attr("transform", "translate( 10, " + doge.graph.textY + ")")
            .attr("class", "legendCircle "+clss);

        txt = g.append("text")
            .attr("x", 20)
            .attr("y", doge.graph.textY)
            .attr("class", "legendText "+clss);

        doge.graph.textY += 15

        var sourceTxt = "";
        switch(clss){
            case "vos":
                sourceTxt = "Vault of Satoshi";
                break;
            case "coinbase":
                sourceTxt = "Cryptsy + Coinbase";
                break;
            case "cryptsy":
                sourceTxt = "Cryptsy";
                break;
        }

        g.append("text")
            .attr("x", 20)
            .attr("y", doge.graph.textY)
            .attr("class", "legendSourceText "+clss)
            .text(sourceTxt);

        doge.graph.textY += 20;
        return txt;
    },

    addDot : function (graph, clss) {
        var focus = graph.append("g")
            .attr("class", "focus " + clss)
            .style("display", "none");

        focus.append("circle")
            .attr("r", 3.5);

        return focus;
    },

    render : function (data) {


        // define dimensions of graph
        var m = [10, 80, 80, 80]; // margins
        var w = $("#graphContainer").width() - m[1] - m[3]; // width
        var h = 500 - m[0] - m[2]; // height
        var parseDate = d3.time.format("%Y-%m-%d %H:%M:%S").parse,
            bisectDate = d3.bisector(function (d) { return d.time; }).left,
            formatDate = d3.time.format("%b%d, %I:%M %p");
            formatUsd = function (d) {
                return d + " MilliCents";
            },
            formatSat = function (d) {
                return d + " Satoshi";
            };


        // create a simple data array that we'll plot with a line (this array represents only the Y values, X will just be the index location)
        var coinbase = doge.graph.fixDate(data["coinbase"], parseDate);
        var cryptsy = doge.graph.fixDate(data["cryptsy"], parseDate);
        var vos = doge.graph.fixDate(data["vos"], parseDate);


        // X scale will fit all values from data[] within pixels 0-w
        var x = d3.time.scale().domain(d3.extent(coinbase, function (d) { return d.time; })).range([0, w]);

        // Y scale will fit values from 0-10 within pixels h-0 (Note the inverted domain for the y-scale: bigger is up!)
        var usdDomain = doge.graph.mergeDomains(coinbase, vos);
        var satDomain = doge.graph.getDomain(cryptsy);
        var usdY = d3.scale.linear().domain(usdDomain).range([h, 0]);
        var satY = d3.scale.linear().domain(satDomain).range([h, 0]);
        // automatically determining max range can work something like this
        // var y = d3.scale.linear().domain([0, d3.max(data)]).range([h, 0]);


        var coinbaseLine = doge.graph.getLine(coinbase, x, usdY)
        var vosLine = doge.graph.getLine(vos, x, usdY)
        var cryptsyLine = doge.graph.getLine(cryptsy, x, satY)

        // Add an SVG element with the desired dimensions and margin.
        var graph = d3.select("#graph").append("svg:svg")
            .attr("width", w + m[1] + m[3])
            .attr("height", h + m[0] + m[2])
            .append("svg:g")
            .attr("transform", "translate(" + m[3] + "," + m[0] + ")");

        // create yAxis
        var xAxis = d3.svg.axis().scale(x).tickSize(-h).tickSubdivide(true);
            //.tickFormat(d3.time.format("%Y-%m-%d"));
        // Add the x-axis.
        graph.append("svg:g")
            .attr("class", "x axis")
            .attr("transform", "translate(0," + h + ")")
            .call(xAxis);


        // create left yAxis
        var yAxisLeft = d3.svg.axis().scale(usdY).ticks(4).orient("left").tickFormat(function (d) { return d + ' mC'; });
        var yAxisRight = d3.svg.axis().scale(satY).ticks(4).orient("right").tickFormat(function (d) { return d + ' Sat.'; });
        // Add the y-axis to the left
        graph.append("svg:g")
            .attr("class", "y axis usd")
            .attr("transform", "translate(-10,0)")
            .call(yAxisLeft);

        graph.append("svg:g")
            .attr("class", "y axis")
            .attr("transform", "translate(" + (w + 5) + ",0)")
            .call(yAxisRight);

        // Add the line by appending an svg:path element with the data line we created above
        // do this AFTER the axes above so that the line is above the tick-lines
        graph.append("svg:path").attr("d", coinbaseLine(coinbase)).attr("class", "coinbase");
        graph.append("svg:path").attr("d", cryptsyLine(cryptsy)).attr("class", "cryptsy");
        graph.append("svg:path").attr("d", vosLine(vos)).attr("class", "vos");

        var rectWidth = 200;
        var rect = graph.append("g")
            .style("display", "none");

            rect.append("rect")
            .attr("class", "legend")
            .attr("width", rectWidth)
            .attr("height", 140);

        var dateText = rect.append("text")
            .attr("x", 20)
            .attr("y", doge.graph.textY)
            .attr("class", "legendDateText ");

        doge.graph.textY += 22;

        var vosFocus = doge.graph.addDot(graph, "vos"),
            cryptsyFocus = doge.graph.addDot(graph, "cryptsy"),
            coinbaseFocus = doge.graph.addDot(graph, "coinbase"),
            cryptsyText = doge.graph.addText(rect, "cryptsy"),
            vosText = doge.graph.addText(rect, "vos"),
            coinbaseText = doge.graph.addText(rect, "coinbase");

        graph.append("rect")
            .attr("class", "overlay")
            .attr("width", w)
            .attr("height", h)
            .on("mouseover", function () {
                rect.style("display", null);
                coinbaseFocus.style("display", null);
                vosFocus.style("display", null);
                cryptsyFocus.style("display", null);
            })
            .on("mouseout", function () {
                rect.style("display", "none");
                vosFocus.style("display", "none");
                coinbaseFocus.style("display", "none");
                cryptsyFocus.style("display", "none");
            })
            .on("mousemove", mousemove);

        function mousemove() {
            var x0 = x.invert(d3.mouse(this)[0]),
                i = bisectDate(vos, x0, 1),
                d0 = vos[i - 1],
                d1 = vos[i],
                i = x0 - d0.time > d1.time - x0 ? i : i - 1,
                vosPrice = vos[i],
                cryptsyPrice = cryptsy[i],
                coinbasePrice = coinbase[i];

            var xPos = (x(vosPrice.time) + 10);
            if( (xPos + rectWidth) > w){
                xPos -= rectWidth;
                xPos -= 20;
            }

            dateText.text(formatDate(vosPrice.time));
            rect.attr("transform", "translate(" + xPos + "," + 5 + ")");
            vosFocus.attr("transform", "translate(" + x(vosPrice.time) + "," + usdY(vosPrice.value) + ")");
            vosText.text(vosPrice.value + " MilliCents");

            coinbaseFocus.attr("transform", "translate(" + x(coinbasePrice.time) + "," + usdY(coinbasePrice.value) + ")");
            coinbaseText.text(coinbasePrice.value + " MilliCents");

            cryptsyFocus.attr("transform", "translate(" + x(cryptsyPrice.time) + "," + satY(cryptsyPrice.value) + ")");
            cryptsyText.text(cryptsyPrice.value + " Satoshi");
        }


    }

}