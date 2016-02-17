<?php
/**
 * Created by PhpStorm.
 * User: hze
 * Date: 12/11/15
 * Time: 5:20 PM
 */
include("login.php");
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
            height:900px;
            width:100%;
            background-size:cover;

        }

        #googleMap{
            width:700px;
            height:400px;
            border: 5px solid gray;
        }

        #registerRow{
            margin-top:200px;
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

        #linkerror, #success, #wrongcity {
            display: none;
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
                <li class="active"><a href="index.php">Home<span class="sr-only">(current)</span></a></li>
                <li><a href="about.html">About</a></li>
                <li><a href="Developer.html">Developer</a></li>
            </ul>

            <form class="navbar-form navbar-right" method="post">
                <div class="form-group">
                    <input type="email" class="form-control" placeholder="Email" name="loginemail"/>
                </div>

                <div class="form-group">
                    <input type="password" class="form-control" placeholder="***" name="loginpassword"/>
                </div>
                <input type="submit" class="btn btn-success" name="submit" value="Log in"/>
            </form>

        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>


<div class="container" id="topContainer">
    <div class="row" id="registerRow">
        <div class="col-md-4 col-md-offset-1 whiteBackground" id="registerform">
            <form method="post">
                <div class="form-group">
                    <label for="registeraddress">Address:</label>
                    <input type="text" class="form-control" placeholder="Address" name="registeraddress" id="registeraddress"/>
                </div>

                <div class="form-group">
                    <label for="registername">User Name:</label>
                    <input type="text" class="form-control" placeholder="User Name" name="registername" id="registername"/>
                </div>

                <div class="form-group">
                    <label for="registeremail">Email:</label>
                    <input type="email" class="form-control" placeholder="Email" name="registeremail" id="registeremail"/>
                </div>

                <div class="form-group">
                    <label for="registerpassword">Password:</label>
                    <input type="password" class="form-control" placeholder="***" name="registerpassword" id="registerpassword"/>
                </div>
                <input type="submit" class="btn btn-success" name="submit" value="Sign up"/>
                <?php
                if($error){

                    echo '<div class="alert alert-danger">'.addslashes($error).'</div>';
                }
                ?>

            </form>


            <div class="alert alert-danger" id="linkerror">Server down!!</div>
            <div class="alert alert-success" id="success">Success!</div>
            <div class="alert alert-danger" id="wrongcity">Can't find weather of this city. Please try another one.</div>
        </div>

        <div class="col-md-6" id="googleMap">

        </div>
    </div>
</div>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>
<script src="http://maps.googleapis.com/maps/api/js"></script>
    <script type="text/javascript">
    var map;
    var marker;
    var myLatlng = new google.maps.LatLng(40.6407442,-74.0202356);
    var geocoder = new google.maps.Geocoder();
    var infowindow = new google.maps.InfoWindow();

    function initialize(){
        var mapOptions = {
            zoom: 15,
            center: myLatlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP

        };

        map = new google.maps.Map(document.getElementById("googleMap"), mapOptions);

        marker = new google.maps.Marker({
            map: map,
            position: myLatlng,
            draggable: true
        });

        geocoder.geocode({'latLng': myLatlng }, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                if (results[0]) {
                    $('#latitude,#longitude').show();
                    $('#registeraddress').val(results[0].formatted_address);
                    infowindow.setContent(results[0].formatted_address);
                    infowindow.open(map, marker);
                }
            }
        });

        google.maps.event.addListener(marker, 'dragend', function() {

            geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[0]) {
                        $('#registeraddress').val(results[0].formatted_address);
                        infowindow.setContent(results[0].formatted_address);
                        infowindow.open(map, marker);
                    }
                }
            });
        });

    }
    google.maps.event.addDomListener(window, 'load', initialize);
</script>

<script>
    $("#registerbtn").click(function(event){

        event.preventDefault();

        $(".alert").hide();

        $.ajax({
            type:"GET",
            url:"https://maps.googleapis.com/maps/api/geocode/xml?address="+encodeURIComponent($('#registeraddress').val())+"&key=AIzaSyDxgb9HBTqkXFW1AQOJd8dbrtw41bJhOQk",
            dataType:"xml",
            success:processXML,
            error: processError,
        });

        function processXML(xml) {

            var street_number;
            var route;
            var neighborhood;
            var sublocality_level_1;

            if($(xml).find("status").text() == "OK"){

                $(xml).find("address_component").each(function(){

                    if($(this).find("type").text() == "street_number"){

                        //$("#success").html("The postcode you need is: "+$(this).find('long_name').text()).fadeIn();

                        street_number = $(this).find('long_name').text();
                    }

                    if($(this).find("type").text() == "route"){


                        route = $(this).find('long_name').text();
                    }

                });

                alert(street_number + route);
            }else{
                $("#wrongcity").html("Can't find the postcode of this address").fadeIn();
            }

        }

        function processError(){
            $("#linkerror").fadeIn();
        }
    });
</script>

</body>
</html>

