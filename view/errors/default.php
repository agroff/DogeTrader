<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-12"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="description"
          content="Get up to date prices on dogecoin, monitor market changes in real time, convert doge to USD, GBP, and EUR at Doge Trader. To the moon!">
    <title>500 Internal Server Error. DogeTrader</title>
    <link rel="icon" type="img/ico" href="/img/doge-logo.png">
    <link rel="stylesheet" href="css/foundation.css"/>
    <link rel="stylesheet" href="css/doge.css"/>
    <script src="js/vendor/modernizr.js"></script>
</head>
<body class="doge" data-coin="doge">

<a name="top" class="">&nbsp;</a>

<div class="row">
    <div class="large-6 columns small-centered center">
        <img src="/img/doge-logo.png" alt="Dogecoin Logo" class="dogeCoin">

        <h1>
            Doge Trader
        </h1>

        <h3 class="dogeText">Whoops.</h3>

        <p class="dogeText">

            Something went wrong.
            <br>
            Much sorry. Very broken. How start over?
        </p>

        <a href="/" class="small radius button" >Take Me Home.</a>
    </div>
</div>

<a name="market" class="linkMarker">&nbsp;</a>

<?php if(ENVIRONMENT === 'development'): ?>

<div class="devError row">
    <h3>
        Message:
        <?php o($error->getMessage()); ?>
    </h3>
    <h3>
        Code:
        <?php o($error->getCode()); ?>
    </h3>

    <table class="orderbook">
        <tr>
            <th>
                File
            </th>
            <th>
                Line
            </th>
            <th>
                Function
            </th>
            <th>
                Args
            </th>
        </tr>
        <?php foreach($error->getTrace() as $trace): ?>
            <?php
            if(!isset($trace["file"])) {$trace["file"] = "";}
            if(!isset($trace["line"])) {$trace["line"] = "";}
            if(!isset($trace["function"])) {$trace["function"] = "";}
            ?>
            <tr>
                <td>
                    <?php o(str_replace(DOC_ROOT, "", $trace["file"])) ?>
                </td>
                <td>
                    <?php o($trace["line"]) ?>
                </td>
                <td>
                    <?php o($trace["function"]) ?>
                </td>
                <td class="tiny">
                    <?php foreach ($trace["args"] as $arg): ?>
                        <?php if(is_array($arg)): ?>
                            <?php o("array count(".count($arg).") "); ?>
                        <?php else: ?>
                            <?php dbg($arg); ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

</div>
<?php endif; ?>

</body>
</html>