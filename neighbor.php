<?php
/**
 * Created by PhpStorm.
 * User: hze
 * Date: 12/24/15
 * Time: 2:31 PM
 */
session_start();
error_reporting(0);
include("connection.php");
include("DataRetrieval.php");
include("getuseraddress.php");

if($_GET["friendid"]){
    insertFriendWaitingList($link,$_SESSION['id'],$_GET["friendid"]);
}

if($_GET["neighborid"]){
    insertNeighbor($link,$_SESSION['id'],$_GET["neighborid"]);
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Neighbor</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
        #logo{
            position: relative;
            top:-10px;
        }

        #topContainer{
            background-image: url("images/back.jpeg");
            height:300px;
            width:100%;
            background-size:cover;

        }

        #googleMap{
            width:700px;
            height:400px;
            border: 5px solid gray;
        }

        #topRow{
            margin-top:60px;
        }
        #registerform{
            margin-top:20px;
        }

        .whiteBackground{
            margin-right:10px;
            padding:20px;
            background-color: hsla(240, 20%, 95%, 0.8);
            border-radius: 20px;
        }


    </style>
</head>
<body>
<nav class="navbar navbar-inverse navbar-fixed-top" id="topBar">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbarlink" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php"><img src="images/Neighbor.png" id="logo"></a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="navbarlink">
            <ul class="nav navbar-nav ">
                <li><a href="info.php">Info</a></li>
                <li><a href="message.php">Message</a></li>
                <li><a href="friend.php">Friend</a></li>
                <li class="active"><a href="neighbor.php">Neighbor</a></li>
                <li><a href="approve.php">Approve</a></li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                <li><a href="index.php?logout=1">Sign Out</a></li>
            </ul>

        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>
<div class="container" id="topContainer">

</div>

<div class="container">
    <div class="row" id="topRow">

        <h3>Address: <?php
            $getaddressresults = mysqli_fetch_array($getaddressresult);
            echo $getaddressresults['Address'];?></h3>

        <div class="col-md-5 whiteBackground">
            <h3>Exsting Neighbors</h3>
            <?php
            $result = showNeighbor($link,$_SESSION['id']);

            while($results = mysqli_fetch_array($result)){

                echo "<div>
                <h4>".$results['Name']."</h4>
                <h5>".$results['Address']."</h5>
                <button name=\"new\" type=\"button\" class=\"btn btn-primary btn-sm\" data-toggle=\"modal\" data-target=\"#newModal\"  onclick=\"window.location.href = 'neighbor.php?friendid={$results['Userid']}'\">Add friend</button>

            </div>";
            }

            ?>
        </div>

        <div class="col-md-5 whiteBackground">
            <h3>Add more Neighbors</h3>
            <?php
            $result = findnotNeighbor($link,$_SESSION['id'],$_SESSION['blockid']);



            while($results = mysqli_fetch_array($result)){

                echo "<div>
                <h4>".$results['Name']."</h4>
                <h5>".$results['Address']."</h5>
                <button name=\"new\" type=\"button\" class=\"btn btn-primary btn-sm\" onclick=\"window.location.href='neighbor.php?neighborid={$results['Userid']}'\">Add</button>

            </div>";
            }

            ?>
        </div>
    </div>
</div>





<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>

<script>
    $('#newModal').on('show.bs.modal',function(event){
        var button = $(event.relatedTarget)
        var recipient = button.data('whatever')
        var id= button.data('sendto')

        var modal = $(this)

        modal.find('.modal-title').text('Send Message to ' + recipient)
        modal.find('#sendto').val(id)


    })
</script>

</body>
</html>
