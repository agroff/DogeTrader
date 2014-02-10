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
    <meta name="description" content="Get up to date prices on dogecoin, monitor market changes in real time, convert doge to USD, GBP, and EUR at Doge Trader. To the moon!">
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
                <li><a href="#converter">Converter</a></li>
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
            A collection of utilities for trading doge.
            <br>
            All market data is from Cryptsy
        </p>

        <button class="small radius button" id="moon-button" type="button">To The Moon!!!</button>
    </div>
</div>


<a name="market" class="linkMarker">&nbsp;</a>


<div class="row">
    <div class="large-5 columns">
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
    <div class="large-13 columns">
        <?php view("alarm", array("alarms" => $alarms)); ?>
    </div>
</div>


<a name="trades" class="linkMarker">&nbsp;</a>
<?php view("trades") ?>

<?php view("orders") ?>

<a name="calculator" class="linkMarker">&nbsp;</a>

<div class="row">
    <div class="large-6 columns">

    </div>
    <div class="large-6 columns">

    </div>
    <div class="large-6 columns">

    </div>
</div>

<div class="row">
    <div class="large-18 columns">


        <div class="row">
            <div class="large-6 columns">

                <h3>
                    Calculator
                </h3>

                <div class="row collapse">
                    <div class="small-6 columns">
                        <span class="prefix">DOGE</span>
                    </div>
                    <div class="small-12 columns">
                        <input type="text" id="buyDoge" value="10000">
                    </div>
                </div>

                <div class="row collapse">
                    <div class="small-6 columns">
                        <span class="prefix">Sat.</span>
                    </div>
                    <div class="small-12 columns">
                        <input type="text" id="buySat">
                    </div>
                </div>

                <div class="center">
                    <button id="addBuy" type="button" class="small centered radius button">+ Buy</button>
                </div>

            </div>
            <div class="large-6  columns">

                <h5>
                    Buy List
                </h5>

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
            <div class="large-6 columns">


                <h5>
                    Sell
                    <span id="dogeBought"></span>
                    DOGE at
                </h5>

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

<?php view("converter") ?>

<a name="about" class="linkMarker">&nbsp;</a>


<div class="row">
    <div class="large-9 columns">
        <h3>
            About
        </h3>

        <p>
            DogeTrader takes data from cryptsy and displays it in a (hopefully) useful way. It also offers a utility
            to convert to USD/GBP/CAD/EUR, alarms at specified prices, and calculations of your gain/loss at various price points.
        </p>

        <p>
            All active trade data and transaction fees are from <a href="http://cryptsy.com" target="_blank">Cryptsy</a>.
            BTC to fiat exchange rates are courtesy of <a href="https://coinbase.com">Coinbase</a>.
        </p>

        <p>
            Last price is calculated by averaging the last ten transactions on Cryptsy.
        </p>

        <p>
            Donations are happily accepted. Donation address appears during moon celebrations. Moon celebrations and
            alarms will play sound and you can't stop it (sorry). If you really want it to go away, try refreshing the page.
        </p>

        <p>
            Until I get an email address set up at this domain, you can contact me via
            <a href="http://www.reddit.com/user/userNameNotLongEnoug/">reddit</a>.
        </p>
    </div>
    <div class="large-9 columns">

        <h3>
            Updates
        </h3>

        <h6 class="light small underlined">Feb 10, 2014</h6>
        <p>
            Today I switched the fiat conversions from using bitcoin charts to using coinbase. Coinbase is more expensive
            generally but at least they're reliable. I noticed bitcoin charts was pricing 1 BTC at 87 USD which is just
            ridiculous since the actual price was closer $650. I thought their weighted average would provide a good and
            stable exchange agnostic price, but I was so very wrong. I highly recommend not using their API for anything.
        </p>

        <p>
            Today I will also begin storing data for the first time in this site's history. I plan to store the price of doge
            in both satoshi and USD and eventually provide a graph of both prices. The recent fluctuations in BTC to fiat
            are making it more difficult to determine the true direction of DOGE as compared to fiat so I feel this feature
            is necessary.
        </p>

        <p>
            Some other ideas: I want to add a section with mining/halving data. I'm considering getting data from other
            exchanges, especially vault of satoshi for direct DOGE/USD data. I am also considering text to speech monitoring
            of the order book and change log. I also want volume in the form of BTC per 5 minutes, with alarms for high
            volumes. If any of those sound important to you let me know and there's a higher chance I'll get around to
            them. Doge tips also effect my motivation.
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
<?php template("currencyTemplate") ?>
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
<?php
if(defined("ANALYTICS_UA")):
    ?>
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', '<?php o(ANALYTICS_UA) ?>', '<?php o(SITE_URL) ?>');
        ga('send', 'pageview');

    </script>
<?php
endif;
?>
</body>
</html>
