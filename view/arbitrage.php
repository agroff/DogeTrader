<?php
function formatSatoshi($satoshi)
{
    if ($satoshi < 1000000) {
        echo number_format($satoshi);
    } else {
        echo number_format($satoshi / 100000000, 7);
    }

}

?>
<div class="row">

    <div class=" columns">

        <table class="large-18">

            <tr style="text-align: left;" >


                <th>Coin</th>
                <th>Format</th>
                <?php foreach ($marketList as $market): ?>
                    <th><?php o(ucfirst($market)); ?></th>
                <?php endforeach; ?>
                <!--
                <th>High</th>
                <th>Low</th>
                -->
                <th>Profit</th>
                <th>Overview</th>
                <th>&nbsp;</th>
                <th>Report</th>
            </tr>


            <?php foreach ($arbitrage as $item) : ?>


                <tr data-coin="<?php o($item["currency"]); ?>"
                    data-buyAt="<?php o($item["buyAt"]); ?>"
                    data-sellAt="<?php o($item["sellAt"]); ?>">

                    <td>
                        <?php o($item["currency"]); ?>
                    </td>
                    <td>
                        <?php
                        if ($item["high"] > 1000000) {
                            echo "BTC";
                        }
                        else {
                            echo "Satoshi";
                        }
                        ?>
                    </td>

                    <?php foreach ($marketList as $market): ?>
                        <td>
                            <?php
                            if (isset($item["list"][$market])) {
                                formatSatoshi($item["list"][$market]->price);
                            } else {
                                o('---');
                            }


                            ?>
                        </td>
                    <?php endforeach; ?>

                    <!--
                    <td>
                        <?php formatSatoshi($item["high"]); ?>
                    </td>

                    <td>
                        <?php formatSatoshi($item["low"]); ?>
                    </td>

                    -->

                    <td>
                        <?php o(round($item["percent"], 2)); ?> %
                    </td>
                    <td>
                        Buy at: <?php o($item["buyAt"]); ?>
                        <br>
                        Sell at: <?php o($item["sellAt"]); ?>
                    </td>
                    <td>
                        <button class="tiny radius button feasibilityButton">
                            Feasibility
                        </button>
                    </td>
                    <td class="report large-6">
                        ---
                    </td>
                </tr>

            <?php endforeach ?>


        </table>


    </div>


</div>


<?php template("arbitrageReport") ?>