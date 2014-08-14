<?php

if(!isset($fullHeader)){
    $fullHeader = true;
}

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


<div id="donations" class="comic">
    Such Donations:
    <?php o($donationAddress) ?>

</div>


<a name="top" class="">&nbsp;</a>

<div class="fixed">
    <nav class="top-bar" data-topbar>
        <ul class="title-area">
            <li class="name">
                <h1><a href="/#top"><?php o($ucCoinName); ?>Trader</a></h1>
            </li>
        </ul>

        <section class="top-bar-section">
            <ul class="left">
                <li>
                    <a href="javascript:void(0);" id="donateButton">
                        Total Donations: <?php o($donations); ?> <?php o($symbol); ?>
                    </a>
                </li>
                <li class="has-dropdown">
                    <a href="javascript:void(0);"  id="currentMarket">Cryptsy</a>
                    <ul class="dropdown" id="selectMarket">
                        <li><a href="javascript:void(0);">Cryptsy</a></li>
                        <li><a href="javascript:void(0);">MintPal</a></li>
                        <li><a href="javascript:void(0);">Poloniex</a></li>
                    </ul>
                </li>
            </ul>
        </section>

        <?php if($fullHeader): ?>
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
        <?php  endif; ?>
    </nav>
</div>

<div class="row">
    <div class="large-6 columns small-centered center">
        <img src="/img/<?php o($coinName); ?>-logo.png" alt="<?php o($ucCoinName); ?>coin Logo" class="dogeCoin">

        <h1>
            <?php o($ucCoinName); ?> Trader
        </h1>

        <p class="dogeText">
            A collection of utilities for trading <?php o($coinName); ?>.
            <br>
        </p>

        <button class="small radius button" id="moon-button" type="button">To The Moon!!!</button>
    </div>
</div>
