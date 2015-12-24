<?php
/**
 * Created by PhpStorm.
 * User: hze
 * Date: 12/23/15
 * Time: 8:38 PM
 */
session_start();

include("connection.php");

$getexsitinginfo = "SELECT * FROM User WHERE UserId = '".$_SESSION['id']."'";

$result = mysqli_query($link, $getexsitinginfo);

$results = mysqli_fetch_array($result);

if(isset($_POST['submit']) && $_POST['submit']=='Update'){
    $updateinfo = "UPDATE User SET Name= '".$_POST['updatename']."',Age='".$_POST['updateage']."',Gender='".$_POST['updategender']."',Phone1='".$_POST['updatephone1']."',
    Phone2='".$_POST['updatephone2']."',Address='".$_POST['updateaddress']."',Intro='".$_POST['updateintro']."',NotifyMessage='".$_POST['updatenotifymessage']."',NotifyType='".$_POST['updatenotifytype']."'
    WHERE UserId = '".$_SESSION['id']."'";

    if(mysqli_query($link,$updateinfo)){
        $success = "User Infomation Updated Successfully";
    }else{
        $error = "Update Failure";
    }


}

?>