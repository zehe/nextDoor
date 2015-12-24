<?php
/**
 * Created by PhpStorm.
 * User: hze
 * Date: 12/13/15
 * Time: 4:41 PM
 */
 
 include("getmessage.php");
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
            <a class="navbar-brand" href="#"><img src="images/Neighbor.png" id="logo"></a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="navbarlink">
            <ul class="nav navbar-nav ">
                <li><a href="info.php">Info</a></li>
                <li class="active"><a href="message.php">Message</a></li>
                <li><a href="#">Friend</a></li>
                <li><a href="#">Approve</a></li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                <li><a href="#">Sign Out</a></li>
            </ul>

        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>
<div class="container" id="topContainer">

</div>

<div class="container">
    <div class="row" id="topRow">

        <h3>Address: 348 61st St.</h3>
        <div class="col-md-2">
            <div class="btn-group-vertical text-center" role="group" aria-label="...">
                <button type="button" class="btn btn-default" data-toggle="modal" data-target="#newModal">New</button>
                <form method="post">
                    <input type="submit" class="btn btn-default form-control" value="Hood">
                    <input type="submit" class="btn btn-default form-control" value="Block">
                    <input type="submit" class="btn btn-default form-control" value="Friend">
                </form>

            </div>
        </div>

        <div class="col-md-9 whiteBackground">
            <?php
            if($success){
                echo "<div class='alert alert-success'>".$success."</div>";
            }

            if($error){
                echo "<div class='alert alert-danger'>".$error."</div>";
            }
            ?>

            <?php
                while($results = mysqli_fetch_array($result)){
                    //$_SESSION['replyid']=$results['PostId'];
                    echo "<div>
                <h4>".$results['Subject']."</h4>
                <h5>".$results['Title']."</h5>
                <p>".$results['Data']."</p>
                <span class=\"label label-default\">".$results['PostTime']."</span>

                <button name=\"reply\" type=\"button\"  class=\"btn btn-primary btn-sm\" data-toggle=\"modal\" data-target=\"#replyModal\" data-whatever='".$results['Name']."'>Reply</button>

            </div>";

                }
            ?>

        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="replyModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Reply</h4>
            </div>
            <form method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Subject</label>
                        <input class="form-control" type="text" name="replysubject" placeholder="subject">
                    </div>

                    <div class="form-group">
                        <label>Title</label>
                        <input class="form-control" type="text" name="replytitle" placeholder="title">
                    </div>

                    <div class="form-group">
                        <label>Content</label>
                        <textarea class="form-control" name="replycontent" rows="10" cols="80" class="center-block">Write something here</textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="submit" name="submit" class="btn btn-primary" value="Reply">
                </div>
            </form>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" tabindex="-1" role="dialog" id="newModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">New</h4>
            </div>
            <form method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Subject</label>
                        <input class="form-control" type="text" name="newsubject" placeholder="subject" value="">
                    </div>

                    <div class="form-group">
                        <label>Title</label>
                        <input class="form-control" type="text" name="newtitle" placeholder="title">
                    </div>

                    <div class="form-group">
                        <label>Content</label>
                        <textarea class="form-control" name="newcontent" rows="10" cols="80" class="center-block">Write something here</textarea>
                    </div>
                </div>
                <div class="modal-footer">

                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="submit" name="submit" class="btn btn-primary" value="Post">
                </div>
            </form>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>

<script>
    $('#replyModal').on('show.bs.modal',function(event){
        var button = $(event.relatedTarget)
        var recipient = button.data('whatever')

        var modal = $(this)

        modal.find('.modal-title').text('Reply Message to ' + recipient)
    })
</script>

</body>
</html>


