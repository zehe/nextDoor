<?php
/**
 * Created by PhpStorm.
 * User: hze
 * Date: 12/23/15
 * Time: 11:50 PM
 */
include("connection.php");
$getallmessage = "SELECT * FROM Message";

$result = mysqli_query($link, $getallmessage);

$results = mysqli_fetch_array($result);

?>