<?php
/**
 * Created by PhpStorm.
 * User: hze
 * Date: 12/23/15
 * Time: 5:44 PM
 */
error_reporting(0);
include("connection.php");

$query = "SELECT * FROM Block";

$result = mysqli_query($link, $query);

while($results = mysqli_fetch_array($result)){
    echo $results["BlockName"];
    echo "<br/>";
}

?>