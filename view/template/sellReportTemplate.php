<?php $symbol = \Groff\Doge\Setting::coin("symbol"); ?>
<div class="row smallTable">
    <div class="large-6 columns">
        Spent:
    </div>

    <div class="large-9 columns">
        <span class="strong" data-content="spent"></span>
    </div>

    <div class="large-3 columns">
        BTC
    </div>
</div>

<div class="row smallTable">
    <div class="large-6 columns">
        Received:
    </div>

    <div class="large-9 columns">
        <span class="strong" data-content="earned"></span>
    </div>

    <div class="large-3 columns">
        BTC
    </div>
</div>

<div class="row smallTable">
    <div class="large-6 columns">
        Net Gain:
    </div>

    <div class="large-9 columns">
        <span class="strong" data-content="gained"></span>
        <br>
        <span class="strong" data-content="gainPercent"></span>
    </div>

    <div class="large-3 columns">
        BTC <br> %
    </div>
</div>

<div class="row smallTable">
    <div class="large-6 columns">
        Gain in <?php o($symbol) ?>:
    </div>

    <div class="large-9 columns">
        <span class="strong" data-content="dogeGain"></span>
    </div>

    <div class="large-3 columns">
        <?php o($symbol) ?>
    </div>
</div>
<div class="row smallTable">
    <div class="large-6 columns">
        Gain in USD:
    </div>

    <div class="large-9 columns">
        <span class="strong" data-content="usdGain"></span>
    </div>

    <div class="large-3 columns">
        USD
    </div>
</div>

