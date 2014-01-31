<?php
require_once("init.php");
use \Groff\Doge\Setting;

$alarms = Setting::get("alarms");
?>
<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-12"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>DogeTrader | wow </title>
    <link rel="icon" type="img/ico" href="/img/doge.png">
    <link rel="stylesheet" href="css/foundation.css"/>
    <link rel="stylesheet" href="css/doge.css"/>
    <script src="js/vendor/modernizr.js"></script>
</head>
<body>

<a name="top" class="">&nbsp;</a>

<div class="fixed">
    <nav class="top-bar" data-topbar>
        <ul class="title-area">
            <li class="name">
                <h1><a href="#top">DogeTrader</a></h1>
            </li>
        </ul>

        <section class="top-bar-section">
            <!-- Right Nav Section -->
            <ul class="right">
                <li class="active"><a href="#market">Market</a></li>
                <li class="divider"></li>
                <li><a href="#orders">Orders</a></li>
                <li class="divider"></li>
                <li><a href="#calculator">Calculator</a></li>
                <li class="divider"></li>
                <li><a href="#converter">Coverter</a></li>
                <li class="divider"></li>
                <li><a href="#about">About</a></li>
            </ul>

        </section>
    </nav>
</div>

<div class="row">
    <div class="large-6 columns small-centered center">
        <img src="/img/doge.png" alt="Dogecoin Logo" class="dogeCoin">

        <h1>
            Doge Trader
        </h1>

        <p class="doge">
            A collection of utilities for trading doge. All pricing data is from Cryptsy
        </p>

        <button class="small radius button" id="moon-button" type="button">To The Moon!!!</button>
    </div>
</div>


<a name="market" class="linkMarker">&nbsp;</a>


<div class="row">
    <div class="large-6 columns">
        <h3>
            Last Price
        </h3>

        <div class="panel">
            <div id="currentPrice">
                ...
            </div>
            <div class="center doge">Satoshi</div>
        </div>
    </div>
    <div class="large-12 columns">
        <?php view("alarm", array("alarms" => $alarms)); ?>
    </div>
</div>


<div class="row">
    <div class="small-6 columns">
        <h3>
            Recent Trade Analysis
        </h3>

    </div>
    <div class="small-6 columns">

        <div class="light small pad-top">
            200 Trades from
            <span id="tradesStart"></span>
            to
            <span id="tradesEnd"></span>
        </div>

    </div>
    <div class="small-6 columns">

        <div id="tradePercentContainer">
            <div id="buyContainer" title="Buy Percentage"></div>
            <div id="sellContainer" title="Sell Percentage"></div>
        </div>

    </div>
</div>

<div class="row">
    <div class="large-8 columns">


        <table class="small large-18">
            <thead>
            <th colspan="3">Top 5 Trades</th>
            </thead>
            <tbody id="topTrades">

            </tbody>

        </table>
    </div>
    <div class="large-5 columns" id="buyStats">

    </div>
    <div class="large-5 columns" id="sellStats">

    </div>
</div>

<?php view("orders") ?>

<a name="calculator" class="linkMarker">&nbsp;</a>

<div class="row">
    <div class="small-6 columns">
        <h3>
            Calculator
        </h3>
    </div>
    <div class="small-6 columns">
        <h5>
            Buy List
        </h5>
    </div>
    <div class="small-6 columns">
        <h5>
            Sell
            <span id="dogeBought"></span>
            DOGE at
        </h5>

    </div>
</div>

<div class="row">
    <div class="large-18 columns">


        <div class="row">
            <div class="small-6 columns">

                <div class="row collapse">
                    <div class="large-6 columns">
                        <span class="prefix">DOGE</span>
                    </div>
                    <div class="large-12 columns">
                        <input type="text" id="buyDoge" value="10000">
                    </div>
                </div>

                <div class="row collapse">
                    <div class="large-6 columns">
                        <span class="prefix">Sat.</span>
                    </div>
                    <div class="large-12 columns">
                        <input type="text" id="buySat">
                    </div>
                </div>

                <div class="center">
                    <button id="addBuy" type="button" class="small centered radius button">+ Buy</button>
                </div>

            </div>
            <div class="small-6 columns">

                <div class="panel tablePanel">

                    <div class="row smallTable strong">
                        <div class="small-5 columns">
                            Doge
                        </div>
                        <div class="small-4 columns">
                            Sat.
                        </div>
                        <div class="small-9 columns">
                            Total BTC
                        </div>
                    </div>
                    <div id="buyList">

                    </div>

                    <div class="row smallTable strong">
                        <div class="small-5 columns" id="buyDogeTotal">
                        </div>
                        <div class="small-4 columns">
                            --
                        </div>
                        <div class="small-9 columns" id="buyBtcTotal">
                        </div>
                    </div>
                </div>

            </div>
            <div class="small-6 columns">

                <div class="row collapse">
                    <div class="large-6 columns">
                        <span class="prefix">Sat.</span>
                    </div>
                    <div class="large-12 columns">
                        <input type="text" id="sellSat">
                    </div>
                </div>

                <div class="panel" id="sellReport">
                    Sell Report...
                </div>

            </div>
        </div>

    </div>
</div>

<a name="converter" class="linkMarker">&nbsp;</a>


<div class="row">
    <div class="large-18 columns">
        <h3>
            Converter
        </h3>
    </div>
</div>
<div class="row">
    <div class="large-6 columns">
        <div class="row collapse">
            <div class="large-6 columns">
                <span class="prefix">DOGE</span>
            </div>
            <div class="large-12 columns">
                <input type="text" placeholder="Enter DOGE Amount" value="10000" id="convertDoge">
            </div>
        </div>
        <div class="row collapse">
            <div class="large-6 columns">
                <span class="prefix">BTC</span>
            </div>
            <div class="large-12 columns">
                <input type="text" disabled="true" id="convertBtc">
            </div>
        </div>
        <div class="row collapse">
            <div class="large-6 columns">
                <span class="prefix">USD</span>
            </div>
            <div class="large-12 columns">
                <input type="text" disabled="true" id="convertUsd">
            </div>
        </div>

        <div class="center">
            <button id="doConversion" type="button" class="small centered radius button">Convert</button>
        </div>
    </div>
</div>
</div>


<a name="about" class="linkMarker">&nbsp;</a>


<div class="row">
    <div class="large-18 columns">
        <h3>
            About
        </h3>

        <p>
            DogeTrader takes data from cryptsy and displays it in a (hopefully) useful way. It also offers a utility
            to convert to USD, alarms at specified prices, and calculations of your gain/loss at various price points.
        </p>

        <p>
            All active trade data and transaction fees are from <a href="http://cryptsy.com" target="_blank">Cryptsy</a>.
            BTC to fiat exchange rates are courtesy of <a href="http://bitcoincharts.com/">Bitcoin Charts</a>.
        </p>

        <p>
            Last price is calculated by averaging the last ten transactions on Cryptsy.
        </p>

        <p>
            Donations are happily accepted. Donation address appears during moon celebrations.
        </p>
    </div>
</div>


<img id="rocket" class="celebrate" src="/img/rocket-doge.gif">
<img id="mooon" class="celebrate" src="/img/moon.png">

<div id="bottomSpacer">
    &nbsp;
</div>

<div id="donations" class="comic">
    Such Donations: DLHK5ra1F2gqyLRbjoASWXZCfVLX8PVViq
</div>

<div id="errorModal" class="reveal-modal small" data-reveal>
    <h3 class="errorTitle">Wow. Such Error. Very Problem.</h3>
    <p id="errorContent">

    </p>
    <a class="close-reveal-modal small radius button">Okay</a>
</div>

<?php template("transactionTemplate") ?>
<?php template("transactionStatsTemplate") ?>
<?php template("buyTemplate") ?>
<?php template("alarmTemplate") ?>
<?php template("orderTemplate") ?>
<?php template("changeTemplate") ?>
<?php template("triggeredAlarmTemplate") ?>
<?php template("sellReportTemplate") ?>


<?php //End Templates ?>

<script src="js/doge/main.php"></script>
<script>
    $(document).foundation();
    $(function () {
        doge.main();
    })
</script>
</body>
</html>
