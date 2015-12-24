<?php
/**
 * Created by PhpStorm.
 * User: bowang
 * Date: 12/23/15
 * Time: 16:56
 */
include("DataRetrieval.php");
if(connect()==false){
    echo "connect_fail!";
}
else{
    $conn = connect();
    echo "connect_done ";
}

#echo mysqli_num_rows($res);
if(insertApproveList($conn,99,2)){
    echo "ok";
}else {
    echo "die";
}
?>