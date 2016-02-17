<?php
/**
 * Created by PhpStorm.
 * User: hze
 * Date: 12/23/15
 * Time: 8:38 PM
 */
session_start();
include("DataRetrieval.php");

include("connection.php");
error_reporting(0);

$success = '';
$error = '';

$getexsitinginfo = "SELECT * FROM User WHERE UserId = '".$_SESSION['id']."'";

$result = mysqli_query($link, $getexsitinginfo);

$results = mysqli_fetch_array($result);

if(isset($_POST['submit']) && $_POST['submit']=='Update'){

 //   if($_SESSION['blockid']){
//        echo "here";
//        //echo getBlockId($link,$array[1],$array[2]);
//        echo "here1";
        $array = getaddress($_POST['updateaddress']);
       if(isMoveout($link,$array[1],$array[2],$_SESSION['blockid'])){
//            echo "moveout";
            insertMoveIn($link,$_SESSION['id'],getBlockId($link,$array[1],$array[2]));
//            echo "here";
            insertWaitingList($link,$_SESSION['id'],getBlockId($link,$array[1],$array[2]));
       }
 //   }


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