<?php

if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start();
/*
Load app bootstrap file
*/
require '../app/bootstrap.php';

/*
Call slim app run method to run this application
*/

$app->run();