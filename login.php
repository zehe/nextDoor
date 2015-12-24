<?php
/**
 * Created by PhpStorm.
 * User: hze
 * Date: 11/20/15
 * Time: 2:46 PM
 */
session_start();

include("connection.php");


if(isset($_POST['submit'])){

    if($_POST['submit']=="Sign up"){

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
                $addnewuserquery = "INSERT INTO `User`(`Email`, `Password`,`Address`,`Name`) VALUES ('".$_POST['registeremail']."','".$_POST['registerpassword']."','".$_POST['registeraddress']."','".$_POST['registername']."')";
                mysqli_query($link,$addnewuserquery);

                $_SESSION['id']= mysqli_insert_id($link);

                header("Location:message.php");

            }
        }
    }

    if ($_POST['submit']=="Log in") {

        $loginquery= "SELECT * FROM User WHERE Email='".$_POST['loginemail']."' AND Password='".$_POST['loginpassword']."'";
        $loginresult = mysqli_query($link,$loginquery);
        $rows = mysqli_fetch_array($loginresult);

        if($rows){
            $_SESSION['id']=$rows['UserId'];
            header('Location:message.php');
            //Redirect to logged in page
        }else{
            $error = "We could not find a user with that email and password!";
        }

    }
}



?>
