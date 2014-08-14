<br>
<br>
<br>
<div class="center dogetrader-family">
    <a href="http://mint.dogetrader.co">MintTrader</a>
    <span> | </span>
    <a href="http://dogetrader.co">DogeTrader</a>
</div>

<img id="rocket" class="celebrate" src="/img/rocket-doge.gif">
<img id="mooon" class="celebrate" src="/img/moon.png">

<div id="bottomSpacer">
    &nbsp;
</div>


<div id="errorModal" class="reveal-modal small" data-reveal>
    <h3 class="errorTitle" id="errorTitle">Wow. Such Error. Very Problem.</h3>

    <p id="errorContent">

    </p>
    <a class="close-reveal-modal small radius button">Okay</a>
</div>



<script src="js/doge/main.php"></script>
<script>
    $(document).foundation();
    $(function () {
        doge.main();
    })
</script>
<?php
if (defined("ANALYTICS_UA")):
    ?>
    <script>
        (function (i, s, o, g, r, a, m) {
            i['GoogleAnalyticsObject'] = r;
            i[r] = i[r] || function () {
                (i[r].q = i[r].q || []).push(arguments)
            }, i[r].l = 1 * new Date();
            a = s.createElement(o),
                m = s.getElementsByTagName(o)[0];
            a.async = 1;
            a.src = g;
            m.parentNode.insertBefore(a, m)
        })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

        ga('create', '<?php o(ANALYTICS_UA) ?>', '<?php o(SITE_URL) ?>');
        ga('send', 'pageview');

    </script>
<?php
endif;
?>
</body>
</html>