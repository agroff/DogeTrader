
<div class="row">
    <div class="small-6 columns">
        <div class="row">
            <div class="small-12 columns">

                <h3>
                    Trade Analysis
                </h3>

            </div>
            <div class="small-6 columns">

                <select id="analyzeCount">
                    <option value="25">Last 25</option>
                    <option value="50">Last 50</option>
                    <option value="75">Last 75</option>
                    <option value="100">Last 100</option>
                    <option value="150">Last 150</option>
                    <option value="200">Last 200</option>
                </select>

            </div>
        </div>

    </div>
    <div class="small-6 columns">

        <div class="light small pad-top text-center">
             Showing trades from
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