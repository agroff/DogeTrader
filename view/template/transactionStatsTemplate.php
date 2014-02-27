<?php $symbol = \Groff\Doge\Setting::coin("symbol"); ?>
<table class="large-18">
    <tr>
        <th colspan="2" data-content="title"></th>
    </tr>
    <tr>
        <td class="strong">Count</td>
        <td data-content="count"></td>
    </tr>
    <tr>
        <td class="strong">Average BTC</td>
        <td data-content="btcAverage"></td>
    </tr>
    <tr>
        <td class="strong">Total BTC</td>
        <td data-content="btcTotal"></td>
    </tr>
    <tr>
        <td class="strong">Average <?php o($symbol); ?></td>
        <td data-content="dogeAverage"></td>
    </tr>
    <tr>
        <td class="strong">Total <?php o($symbol); ?></td>
        <td data-content="dogeTotal"></td>
    </tr>
</table>