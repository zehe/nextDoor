<?php
/**
 * Created by PhpStorm.
 * User: bowang
 * Date: 12/24/15
 * Time: 23:28
 */
include("updateinfo.php");
error_reporting(0);
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
            background-attachment: fixed;
            height:900px;
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
                <li class="active"><a href="NotMemberYet.php">Info</a></li>
                <li><a href="NoAuthority.php">Message</a></li>
                <li><a href="NoAuthority.php">Friend</a></li>
                <li><a href="NoAuthority.php">Approve</a></li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                <li><a href="index.php?logout=1">Sign Out</a></li>
            </ul>

        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>


<div class="container" id="topContainer">
    <div class="row" id="topRow">
        <div class="col-md-6 col-md-offset-3 whiteBackground">
            <form method="post">
                <div class="form-group">
                    <label for="updatename">Name:</label>
                    <input type="text" class="form-control" placeholder="Name" name="updatename" value="<?php echo $results['Name'];?>"/>
                </div>

                <div class="form-group">
                    <label for="updateage">Age:</label>
                    <input type="text" class="form-control" placeholder="Age" name="updateage" value="<?php echo $results['Age'];?>"/>
                </div>

                <div class="form-group">
                    <label>Gender:</label>
                    <label class="radio-inline"><input type="radio" name="updategender" value="male" <?php if($results['Gender'] == 'male'): ?> checked="checked" <?php endif; ?> >Male</label>
                    <label class="radio-inline"><input type="radio" name="updategender" value="female" <?php if($results['Gender'] == 'female'): ?> checked="checked" <?php endif; ?>>Female</label>
                </div>

                <div class="form-group">
                    <label for="updatephone1">Phone1:</label>
                    <input type="text" class="form-control" placeholder="Phone1" name="updatephone1" value="<?php echo $results['Phone1'];?>"/>

                    <label for="updatephone2">Phone2:</label>
                    <input type="text" class="form-control" placeholder="Phone2" name="updatephone2" value="<?php echo $results['Phone2'];?>"/>
                </div>

                <div class="form-group">
                    <label for="updateemail">Email:</label>
                    <input type="text" class="form-control" placeholder="Address" name="updateemail" value="<?php echo $results['Email'];?>" disabled/>
                </div>

                <div class="form-group">
                    <label for="updateaddress">Address:</label>
                    <input type="text" class="form-control" placeholder="Address" name="updateaddress" value="<?php echo $results['Address'];?>"/>
                </div>

                <div class="form-group">
                    <label for="updateintro">Intro:</label>
                    <input type="text" class="form-control" placeholder="Intro" name="updateintro" value="<?php echo $results['Intro'];?>"/>
                </div>

                <div class="form-group">
                    <label>Notify Message:</label>
                    <label class="radio-inline"><input type="radio" name="updatenotifymessage" value="notifyall" <?php if($results['NotifyMessage'] == 'notifyall'): ?> checked="checked" <?php endif; ?> >Notify All Message</label>
                    <label class="radio-inline"><input type="radio" name="updatenotifymessage" value="notifyfriend" <?php if($results['NotifyMessage'] == 'notifyfriend'): ?> checked="checked" <?php endif; ?>>Notify Friends' Message only</label>

                    <br />
                    <label>Notify Type:</label>
                    <label class="radio-inline"><input type="radio" name="updatenotifytype" value="email" <?php if($results['NotifyType'] == 'email'): ?> checked="checked" <?php endif; ?> >Notify by email</label>
                    <label class="radio-inline"><input type="radio" name="updatenotifytype" value="sms" <?php if($results['NotifyType'] == 'sms'): ?> checked="checked" <?php endif; ?>>Notify by sms</label>

                </div>

                <input type="submit" class="btn btn-success" name="submit" value="Update"/>

                <?php
                if($success){
                    echo "<div class='alert alert-success'>".$success."</div>";
                }

                if($error){
                    echo "<div class='alert alert-danger'>".$error."</div>";
                }
                ?>
            </form>


        </div>
    </div>
</div>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>
</body>
</html>
