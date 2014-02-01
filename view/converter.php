<a name="converter" class="linkMarker">&nbsp;</a>


<div class="row">
    <div class="large-18 columns">
        <h3>
            Converter
        </h3>
    </div>
</div>

<div class="row">

    <div class="large-4 columns">

        <div class="row collapse">
            <div class="large-6 columns">
                <span class="prefix">DOGE</span>
            </div>
            <div class="large-12 columns">
                <input type="text" placeholder="Enter DOGE Amount" value="100000" id="convertDoge">
            </div>
        </div>

        <div class="row collapse">
            <div class="large-6 columns">
                <span class="prefix">BTC</span>
            </div>
            <div class="large-12 columns">
                <input type="text" id="convertBtc">
            </div>
        </div>

        <div class="row collapse">
            <div class="large-6 columns">
                <span class="prefix">USD</span>
            </div>
            <div class="large-12 columns">
                <input type="text" id="convertUsd">
            </div>
        </div>

        <div class="center">
            <!--
            <button id="doConversion" type="button" class="small centered radius button">Convert</button>
            -->
        </div>

    </div>
    <div class="large-5 columns">

        <div class="row collapse">

            <div class="large-6 columns">
                <button type="button" data-value="10000" class="large-14 small radius button doge-convert">10,000</button>
            </div>
            <div class="large-6 columns">
                <button type="button" data-value="100000" class="large-14 small radius button doge-convert">100,000</button>
            </div>
            <div class="large-6 columns">
                <button type="button" data-value="1000000" class="large-14 small radius button doge-convert">1,000,000</button>
            </div>
        </div>

        <div class="row collapse">
            <div class="large-6 columns">
                <button type="button" data-value="0.01" class="large-14 small radius button btc-convert">1/10</button>
            </div>
            <div class="large-6 columns">
                <button type="button" data-value="0.25" class="large-14 small radius button btc-convert">1/4</button>
            </div>
            <div class="large-6 columns">
                <button type="button" data-value="0.5" class="large-14 small radius button btc-convert">1/2</button>
            </div>
        </div>

        <div class="row collapse">
            <div class="large-6 columns">
                <button type="button" data-value="10" class="large-14 small radius button usd-convert">$10</button>
            </div>
            <div class="large-6 columns">
                <button type="button" data-value="100" class="large-14 small radius button usd-convert">$100</button>
            </div>
            <div class="large-6 columns">
                <button type="button" data-value="1000" class="large-14 small radius button usd-convert">$1,000</button>
            </div>
        </div>

    </div>
    <div class="large-5 columns">

        <div class="panel tablePanel">

            <div class="row smallTable strong">

                <div class="small-18 columns">
                    For Non Americans...
                </div>
            </div>
            <div id="otherCurrencies">
            </div>

        </div>
    </div>
    <div class="large-4 columns">
    </div>
</div>