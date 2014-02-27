<?php
require_once("init.php");
use \Groff\Doge\Setting;
use \Groff\Doge\Provide\CryptsyMarket;
use \Groff\Doge\ApiFactory;

/** @var \Groff\Doge\Provide\BlockChainInterface $blockChain */
$blockChain = ApiFactory::blockChain(CURRENT_COIN);

$alarms = Setting::get("alarms");

$donationAddress = Setting::coin("donation_address");
$donations = $blockChain->receivedAt($donationAddress);


$coinName = Setting::coin("name");
$symbol = Setting::coin("symbol");
$ucCoinName = ucfirst($coinName);
?>
<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-12"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="description"
          content="Get up to date prices on <?php o($coinName); ?>coin, monitor market changes in real time, convert <?php
          o($coinName); ?> to USD, GBP, and EUR at <?php o($ucCoinName); ?> Trader. To the moon!">
    <title><?php o($ucCoinName); ?>Trader | wow </title>
    <link rel="icon" type="img/ico" href="/img/<?php o($coinName); ?>-logo.png">
    <link rel="stylesheet" href="css/foundation.css"/>
    <link rel="stylesheet" href="css/doge.css"/>
    <script src="js/vendor/modernizr.js"></script>
</head>
<body class="<?php o($coinName); ?>" data-coin="<?php o($coinName); ?>">

<a name="top" class="">&nbsp;</a>

<div class="fixed">
    <nav class="top-bar" data-topbar>
        <ul class="title-area">
            <li class="name">
                <h1><a href="#top"><?php o($ucCoinName); ?>Trader</a></h1>
            </li>
        </ul>

        <section class="top-bar-section">
            <ul class="left">
                <li>
                    <a href="javascript:void(0);" id="donateButton">
                        Total Donations: <?php o($donations); ?> <?php o($symbol); ?>
                    </a>
                </li>
            </ul>
        </section>

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
        <img src="/img/<?php o($coinName); ?>-logo.png" alt="Dogecoin Logo" class="dogeCoin">

        <h1>
            <?php o($ucCoinName); ?> Trader
        </h1>

        <p class="dogeText">
            A collection of utilities for trading <?php o($coinName); ?>.
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
            <div class="center dogeText">Satoshi</div>
        </div>
    </div>
    <div class="large-13 columns">
        <?php view("alarm", array("alarms" => $alarms)); ?>
    </div>
</div>


<a name="trades" class="linkMarker">&nbsp;</a>
<?php view("trades") ?>

<?php view("orders") ?>

<a name="graph" class="linkMarker">&nbsp;</a>


<div class="row">
    <div class="large-18 columns" id="graphContainer" style="position: relative;">

        <h3>
            Trends

            <select id="graphPeriod" class="small-5 large-3">
                <option value=".1">Last 2.5 Hours</option>
                <option value=".25">Last 6 Hours</option>
                <option value=".5">Last 12 Hours</option>
                <option value="1" selected="true">Last Day</option>
                <option value="2">Last 2 Days</option>
                <option value="7">Last 7 Days</option>
                <option value="30">Last 30 Days</option>
            </select>
            <select id="graphStyle" class="small-5 large-3">
                <option value="linear">Style: Straight</option>
                <option value="cardinal" selected="true">Style: Smooth</option>
                <option value="basis">Style: Very Smooth</option>
            </select>

            <a class="tiny right radius button graphToggle cryptsy">
                <div class="dot"></div>
                <span>Cryptsy (Sat)</span>
            </a>
            <a class="tiny right radius button graphToggle vos">
                <div class="dot"></div>
                <span>Vault of Satoshi (mC)</span>
            </a>
            <a class="tiny right radius button graphToggle coinbase">
                <div class="dot"></div>
                <span>Cryptsy + Coinbase (Mc)</span>
            </a>

        </h3>
        
        <div id="graph" class="aGraph" ></div>
    </div>
</div>

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
                        <span class="prefix"><?php o($symbol) ?></span>
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
                            <?php o($ucCoinName); ?>
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
                    <?php o($symbol); ?> at
                </h5>

                <div class="row collapse">
                    <div class="large-6 columns">
                        <span class="prefix">Sat.</span>
                    </div>
                    <div class="large-12 columns">
                        <input type="text" id="sellSat">
                    </div>
                </div>

                <div class="panel tablePanel" id="sellReport">
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
            to convert to USD/GBP/CAD/EUR, alarms at specified prices, and calculations of your gain/loss at various
            price points.
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
            alarms will play sound and you can't stop it (sorry). If you really want it to go away, try refreshing the
            page.
        </p>

        <p>
            Until I get an email address set up at this domain, you can contact me via
            <a href="http://www.reddit.com/user/userNameNotLongEnoug/">reddit</a>.
        </p>

        <h3>
            Awesome Links
        </h3>

        <?php view($coinName . "/links") ?>

    </div>
    <div class="large-9 columns">

        <h3>
            Updates
        </h3>

        <?php view($coinName . "/updates") ?>

    </div>
</div>

<br>
<br>
<br>
<div class="center dogetrader-family">
    <a href="http://mint.dogetrader.co">MintTrader</a>
    <span> | </span>
    <a href="http://dogetrader.co">DogeTrader</a>
</div>

<img id="rocket" class="celebrate" src="/img/rocket-doge.gif">
<img id="mooon" class="celebrate" src="/img/moon.png">

<div id="bottomSpacer">
    &nbsp;
</div>


<div id="donations" class="comic">
    Such Donations:
    <?php o($donationAddress) ?>

</div>

<div id="errorModal" class="reveal-modal small" data-reveal>
    <h3 class="errorTitle" id="errorTitle">Wow. Such Error. Very Problem.</h3>

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

<script src="http://d3js.org/d3.v3.min.js" charset="utf-8"></script>
<script src="js/doge/main.php"></script>
<script>
    $(document).foundation();
    $(function () {
        doge.main();
    })
</script>
<?php
if (defined("ANALYTICS_UA")):
    ?>
    <script>
        (function (i, s, o, g, r, a, m) {
            i['GoogleAnalyticsObject'] = r;
            i[r] = i[r] || function () {
                (i[r].q = i[r].q || []).push(arguments)
            }, i[r].l = 1 * new Date();
            a = s.createElement(o),
                m = s.getElementsByTagName(o)[0];
            a.async = 1;
            a.src = g;
            m.parentNode.insertBefore(a, m)
        })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

        ga('create', '<?php o(ANALYTICS_UA) ?>', '<?php o(SITE_URL) ?>');
        ga('send', 'pageview');

    </script>
<?php
endif;
?>
</body>
</html>
