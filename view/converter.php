<?php $symbol = \Groff\Doge\Setting::coin("symbol");
$sign = $symbol[0];
?>
<a name="converter" class="linkMarker">&nbsp;</a>


<div class="row">
    <div class="large-18 columns">
        <h3>
            Converter
        </h3>
    </div>
</div>

<div class="row">

    <div class="large-11 columns">

        <div class="row">
            <div class="large-8 columns">

                <div class="row collapse">
                    <div class="large-6 columns">
                        <span class="prefix"><?php o($symbol); ?></span>
                    </div>
                    <div class="large-12 columns">
                        <input type="text" placeholder="Enter DOGE Amount" value="100000" id="convertDoge">
                    </div>
                </div>


            </div>
            <div class="large-10 columns">


                <div class="row collapse">

                    <div class="small-6 columns">
                        <button type="button" data-value="10000" class="small-14 small radius button doge-convert"><?php o($sign) ?>10,000</button>
                    </div>
                    <div class="small-6 columns">
                        <button type="button" data-value="100000" class="small-14 small radius button doge-convert"><?php o($sign) ?>100,000</button>
                    </div>
                    <div class="small-6 columns">
                        <button type="button" data-value="1000000" class="small-14 small radius button doge-convert"><?php o($sign) ?>1,000,000</button>
                    </div>
                </div>

            </div>
        </div>
        <div class="row">
            <div class="large-8 columns">


                <div class="row collapse">
                    <div class="large-6 columns">
                        <span class="prefix">BTC</span>
                    </div>
                    <div class="large-12 columns">
                        <input type="text" id="convertBtc">
                    </div>
                </div>

            </div>
            <div class="large-10 columns">


                <div class="row collapse">
                    <div class="small-6 columns">
                        <button type="button" data-value="0.01" class="small-14 small radius button btc-convert">1/100 BTC</button>
                    </div>
                    <div class="small-6 columns">
                        <button type="button" data-value="0.25" class="small-14 small radius button btc-convert">1/4 BTC</button>
                    </div>
                    <div class="small-6 columns">
                        <button type="button" data-value="0.5" class="small-14 small radius button btc-convert">1/2 BTC</button>
                    </div>
                </div>

            </div>
        </div>
        <div class="row">
            <div class="large-8 columns">

                <div class="row collapse">
                    <div class="large-6 columns">
                        <span class="prefix">USD</span>
                    </div>
                    <div class="large-12 columns">
                        <input type="text" id="convertUsd">
                    </div>
                </div>

            </div>
            <div class="large-10 columns">


                <div class="row collapse">
                    <div class="small-6 columns">
                        <button type="button" data-value="10" class="small-14 small radius button usd-convert">$10</button>
                    </div>
                    <div class="small-6 columns">
                        <button type="button" data-value="100" class="small-14 small radius button usd-convert">$100</button>
                    </div>
                    <div class="small-6 columns">
                        <button type="button" data-value="1000" class="small-14 small radius button usd-convert">$1,000</button>
                    </div>
                </div>

            </div>
        </div>



    </div>
    <div class="large-7 columns">

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
</div>