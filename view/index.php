
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
            <?php o($ucCoinName); ?>Trader takes data from various exchanges and displays it in a (hopefully) useful way. It also offers a utility
            to convert to USD/GBP/CAD/EUR, alarms at specified prices, and calculations of your gain/loss at various
            price points.
        </p>

        <p>
            BTC to fiat exchange rates are courtesy of <a href="https://coinbase.com">Coinbase</a>.
        </p>

        <p>
            Last price is calculated by averaging the last ten transactions on the selected exchange.
        </p>

        <p>
            Donations are happily accepted. You can find the donation address in the top bar. Alarms will play sound
            and you can't stop it (sorry). If you really want it to go away, try refreshing the page.
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

        <div class="row">
            <div class="large-18 columns">
                <h3 id="settings">
                    Settings
                </h3>
            </div>
        </div>
        <div class="row">
            <div class="large-13 columns">
                <p>
                        Change Log Threshold
                    <span class="light small ">
                        - Determines the smallest changes to appear in the change log
                    </span>
                </p>
            </div>
            <div class="large-5 columns">
                <select id="changeLogThreshold" class="small-18">
                    <option value="0">No Minimum</option>
                    <option value="0.1">0.1 BTC</option>
                    <option value="0.3">0.3 BTC</option>
                    <option value="0.5">0.5 BTC</option>
                    <option value="0.7">0.7 BTC</option>
                    <option value="1">1.0 BTC</option>
                    <option value="1.5">1.5 BTC</option>
                    <option value="2">2.0 BTC</option>
                    <option value="2.5">2.5 BTC</option>
                    <option value="3">3.0 BTC</option>
                    <option value="4">4.0 BTC</option>
                    <option value="5">5.0 BTC</option>
                </select>
            </div>
        </div>

    </div>
</div>



<?php template("transactionTemplate") ?>
<?php template("transactionStatsTemplate") ?>
<?php template("buyTemplate") ?>
<?php template("alarmTemplate") ?>
<?php template("orderTemplate") ?>
<?php template("changeTemplate") ?>
<?php template("satoshiChangeTemplate") ?>
<?php template("currencyTemplate") ?>
<?php template("triggeredAlarmTemplate") ?>
<?php template("sellReportTemplate") ?>


<?php //End Templates ?>

<script src="http://d3js.org/d3.v3.min.js" charset="utf-8"></script>

