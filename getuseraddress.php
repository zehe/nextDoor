<?php
/**
 * Created by PhpStorm.
 * User: hze
 * Date: 12/24/15
 * Time: 5:36 PM
 */
//session_start();
include("connection.php");
error_reporting(0);

$getaddress= "SELECT Address FROM User WHERE UserId='".$_SESSION['id']."'";

$getaddressresult = mysqli_query($link,$getaddress);

?>