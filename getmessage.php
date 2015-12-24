<?php
/**
 * Created by PhpStorm.
 * User: hze
 * Date: 12/23/15
 * Time: 11:50 PM
 */
session_start();
include("connection.php");

$success='';
$error='';




$getallmessage = "SELECT MessageId, Subject, Title, Name, PostTime, PostId, Data FROM Message,User WHERE User.UserId=Message.PostId";

$result = mysqli_query($link, $getallmessage);


if(isset($_POST['submit'])&&$_POST['submit']=='Hood'){
   $_SESSION['messagefilter']='hood';
}

if(isset($_POST['submit'])&&$_POST['submit']=='Block'){
    $_SESSION['messagefilter']='block';
}

if(isset($_POST['submit'])&&$_POST['submit']=='Friend'){
    $_SESSION['messagefilter']='friend';
}


if(isset($_POST['submit'])&& $_POST['submit']=="Post"){
    date_default_timezone_set("America/New_York");
    $timenow = date("Y-m-d H:i:s");

    $newmessage="INSERT INTO `Message`(`Subject`, `Title`,
                  `Data`,`PostTime`,`PostId`) VALUES ('".$_POST['newsubject']."',
                  '".$_POST['newtitle']."','".$_POST['newcontent']."','".$timenow."',
                  '".$_SESSION['id']."')";

   // $newmessage="INSERT INTO `ProjectTest`.`Message` (`Subject`, `Title`, `PostId`, `PostTime`) VALUES ('ww', 'ww', '1', '2015-12-11 23:34:20')";
    if(mysqli_query($link,$newmessage)){
        $success = "Success";
    }else{
        $error = "Failed";
    }

}

if(isset($_POST['submit'])&& $_POST['submit']=="Reply"){
    date_default_timezone_set("America/New_York");
    $timenow = date("Y-m-d H:i:s");


    $replymessage="INSERT INTO `Message`(`Subject`, `Title`,
                  `Data`,`PostTime`,`PostId`,`ReplyId`) VALUES ('".$_POST['replysubject']."',
                  '".$_POST['replytitle']."','".$_POST['replycontent']."','".$timenow."',
                  '".$_SESSION['id']."','2')";

    if(mysqli_query($link,$replymessage)){
        $success = "Success";
    }else{
        $error = "Failed";
    }

}

?>