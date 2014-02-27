#!/usr/bin/env php
<?php

DEFINE("CURRENT_COIN", 'mint');

$_SERVER["DOCUMENT_ROOT"] = __DIR__;


include("init.php");


$command = new Groff\Doge\Command\Main();
$status = $command->run();

exit($status);