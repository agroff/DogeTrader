
<a name="orders" class="linkMarker">&nbsp;</a>


<div class="row">

    <div class="large-7 columns">

        <h3>
            Sell Orders
        </h3>
    </div>
    <div class="large-7 columns">
        <h3>
            Buy Orders
        </h3>
    </div>
    <div class="large-4 columns">

        <h5 class="light pad-top">
            Change Log
        </h5>
    </div>
</div>

<div class="row">

    <div class="large-7 columns">


        <div class="panel tablePanel orderBookTable">

            <?php view("inc/orderbook-header"); ?>

            <div id="sellOrderList">
            </div>

        </div>

    </div>

    <div class="large-7 columns">

        <div class="panel tablePanel orderBookTable" >

            <?php view("inc/orderbook-header"); ?>

            <div id="buyOrderList">
            </div>

        </div>

    </div>
    <div class="large-4 columns">

        <div class="panel tablePanel" id="changelog">

        </div>

    </div>
</div>