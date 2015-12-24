<?php
/**
 * Created by PhpStorm.
 * User: hze
 * Date: 12/23/15
 * Time: 11:50 PM
 */
include("connection.php");

$timenow = date("Y-m-d H:i:s");
$getallmessage = "SELECT * FROM Message";

$result = mysqli_query($link, $getallmessage);


if(isset($_POST['submit'])&& $_POST['submit']=="Post"){

    $newmessage="INSERT INTO `Message`(`Subject`, `Title`,`Data`,`PostTime`) VALUES ('".$_POST['newsubject']."','".$_POST['newtitle']."','".$_POST['newcontent']."','".$timenow."')";

    mysqli_query($link,$newmessage);
}

if(isset($_POST['submit'])&& $_POST['submit']=="Reply"){

}

?>