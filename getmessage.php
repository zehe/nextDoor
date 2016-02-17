<?php
/**
 * Created by PhpStorm.
 * User: hze
 * Date: 12/23/15
 * Time: 11:50 PM
 */
session_start();
error_reporting(0);
include("connection.php");
include("DataRetrieval.php");

$success='';
$error='';




$getallmessage = "SELECT MessageId, Subject, Title, Name, PostTime, PostId, Data FROM Message,User WHERE User.UserId=Message.PostId";

$result = mysqli_query($link, $getallmessage);



if(isset($_POST['submit'])&&$_POST['submit']=='Hood'){
    $success='';
    $error='';
    $result = showHoodMessage($link,$_SESSION['blockid']);
  // $_SESSION['messagefilter']='hood';

}

if(isset($_POST['submit'])&&$_POST['submit']=='Block'){
    $success='';
    $error='';
    //$_SESSION['messagefilter']='block';
    $result = showBlockMessage($link,$_SESSION['blockid']);
}

if(isset($_POST['submit'])&&$_POST['submit']=='Friend'){
    $success='';
    $error='';
    //$_SESSION['messagefilter']='friend';
    $result = showFriendMessage($link,$_SESSION['id']);

}


if(isset($_POST['submit'])&&$_POST['submit']=="Search"){
    $getallmessagesearch = "SELECT MessageId, Subject, Title, Name, PostTime, PostId, Data FROM Message,User WHERE User.UserId=Message.PostId and ( Title LIKE '%".$_POST['search']."%' OR Subject LIKE '%".$_POST['search']."%' OR Data LIKE '%".$_POST['search']."%')";
    $result = mysqli_query($link, $getallmessagesearch);
}


if(isset($_POST['submit'])&& $_POST['submit']=="Post"){
    date_default_timezone_set("America/New_York");
    $timenow = date("Y-m-d H:i:s");
//
//    $newmessage="INSERT INTO `Message`(`Subject`, `Title`,
//                  `Data`,`PostTime`,`PostId`) VALUES ('".$_POST['newsubject']."',
//                  '".$_POST['newtitle']."','".$_POST['newcontent']."','".$timenow."',
//                  '".$_SESSION['id']."')";

   // $newmessage="INSERT INTO `ProjectTest`.`Message` (`Subject`, `Title`, `PostId`, `PostTime`) VALUES ('ww', 'ww', '1', '2015-12-11 23:34:20')";

    if($_POST['sendto']=='hood'){
        insertThread($link,$_POST['newcontent'],'hood', getHoodId($link,$_SESSION['blockid']));
        replyThread($link,$_SESSION['id'],getMaxThread($link),$_POST['newtitle'],$_POST['newsubject'],$_POST['newcontent'],null,null);
    }

    if($_POST['sendto']=='block'){
        insertThread($link,$_POST['newcontent'],'block', $_SESSION['blockid']);
        replyThread($link,$_SESSION['id'],getMaxThread($link),$_POST['newtitle'],$_POST['newsubject'],$_POST['newcontent'],null,null);
    }


}

if(isset($_POST['submit'])&& $_POST['submit']=="Reply"){
    date_default_timezone_set("America/New_York");
    $timenow = date("Y-m-d H:i:s");


    $replymessage="INSERT INTO `Message`(`Subject`, `Title`,
                  `Data`,`PostTime`,`PostId`,`ReplyId`) VALUES ('".$_POST['replysubject']."',
                  '".$_POST['replytitle']."','".$_POST['replycontent']."','".$timenow."',
                  '".$_SESSION['id']."',".$_POST['sendto'].")";

    if(mysqli_query($link,$replymessage)){
        $success = "Success";
    }else{
        $error = "Failed";
    }

}

?>