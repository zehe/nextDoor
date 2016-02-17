<?php
/**
 * Created by PhpStorm.
 * User: hze
 * Date: 11/20/15
 * Time: 2:46 PM
 */
session_start();
error_reporting(0);
include("DataRetrieval.php");

if($_GET["logout"]==1 && $_SESSION['id']) {
    //$message = "you have been logged out!!!";
    session_destroy();
}

include("connection.php");

$error = '';


if(isset($_POST['submit'])){

    if($_POST['submit']=="Sign up"){

        date_default_timezone_set("America/New_York");
        $registertime = date("Y-m-d H:i:s");

        if(!$_POST['registeraddress']){
            $error .= "<br />Please enter your Address.";
        }

        if(!$_POST['registername']){
            $error .= "<br />Please enter your Name.";
        }

        //Validate email address.
        if(!$_POST['registeremail']){
            $error .= "<br />Please enter your email.";
        }else if(!filter_var($_POST['registeremail'],FILTER_VALIDATE_EMAIL)){
            $error .= "<br />Please enter validate email.";
        }

        //Validate password, change password to md5 code.and save it in database.
        if(!$_POST['registerpassword']){
            $error .= "<br />Please enter your password";
        }else if(strlen($_POST['registerpassword'])<8 && !preg_match('`[A-Z]`',$_POST['registerpassword'])){
            $error .= "<br/>Your password is invalid.";
        }

        if($error){
            $error ='You have mistakes with your enter:'.$error;

        }else{
            $checkexistquery = "SELECT * FROM user WHERE email='".$_POST['email']."'";
            $checkesistresult = mysqli_query($link,$checkexistquery);
            $checkexistrows = mysqli_num_rows($checkesistresult);

            if($checkexistrows){
                $error = "this email has registered, Do you want to Log in?";
            }else{



                $addnewuserquery = "INSERT INTO `User`(`Email`, `Password`,`Address`,`Name`,`RegisterTime`,`LastAccessTime`) VALUES ('".$_POST['registeremail']."','".$_POST['registerpassword']."','".$_POST['registeraddress']."','".$_POST['registername']."','".$registertime."','".$registertime."')";
                mysqli_query($link,$addnewuserquery);

                $_SESSION['id']= mysqli_insert_id($link);

                $array = getaddress($_POST['registeraddress']);
                insertWaitingList($link,$_SESSION['id'],getBlockId($link,$array[1],$array[2]));

                //echo getBlockId($link,$array[1],$array[2]);

                header("Location:NotMemberYet.php");

            }
        }
    }

    if ($_POST['submit']=="Log in") {

        date_default_timezone_set("America/New_York");
        $lastaccesstime = date("Y-m-d H:i:s");

        $loginquery= "SELECT * FROM User WHERE Email='".$_POST['loginemail']."' AND Password='".$_POST['loginpassword']."'";
        $loginresult = mysqli_query($link,$loginquery);
        $rows = mysqli_fetch_array($loginresult);

        if($rows){
            $_SESSION['id']=$rows['UserId'];
            $_SESSION['blockid']=$rows['BlockId'];

            $updatelastaccesstime = "UPDATE User SET LastAccessTime='".$lastaccesstime."'WHERE UserId='".$_SESSION['id']."' ";

            mysqli_query($link,$updatelastaccesstime);

            if(isMember($link,$_SESSION['id'])){
                header('Location:message.php');
            }else{
                header('Location:NotMemberYet.php');
            }



            //Redirect to logged in page
        }else{
            $error = "We could not find a user with that email and password!";
        }

    }
}



?>
