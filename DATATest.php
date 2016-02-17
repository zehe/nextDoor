<?php
/**
 * Created by PhpStorm.
 * User: bowang
 * Date: 12/23/15
 * Time: 16:56
 */
error_reporting(0);
include("DataRetrieval.php");



include("getaddress.php");

$temp = getaddress("348 61st St Brooklyn");

echo $temp[0];
echo $temp[1];
echo $temp[2];
?>