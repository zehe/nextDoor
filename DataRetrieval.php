<?php
/**
 * Created by PhpStorm.
 * User: bowang
 * Date: 12/17/15
 * Time: 20:42
 */
error_reporting(0);
//connect databse
function connect(){
   if($conn = mysqli_connect('127.0.0.1:3306','root','',"ProjectTest")){
       return $conn;
   }
    return false;
}
function close($conn){
    mysqli_close($conn);
}

/*
 * functions for MOVEIN
 */
function checkMoveInForInsert($conn, $UserId, $BlockId){
    $res = mysqli_query($conn,"select Blockid from MoveIn where moveuserid = ".$UserId." order by time DESC limit 1");
    $row = mysqli_fetch_row($res);
    echo "!{$row[0]}!";
    if($row[0] == $BlockId){
        return false;
    }
    return true;
}

function insertMoveIn($conn, $UserId, $BlockId){
    $stmt = mysqli_prepare($conn,"insert into MoveIn VALUES (? , ?, now())");
        mysqli_stmt_bind_param($stmt,"ii",$UserId,$BlockId);
        if(mysqli_stmt_execute($stmt)){
            mysqli_stmt_close($stmt);
            return true;
        }
    mysqli_stmt_close($stmt);
    return false;
}

/*
 * functions for neighbor
 */
function insertNeighbor($conn,$NuserId1,$NuserId2){
    $stmt = mysqli_prepare($conn,"insert into neighbor values (?,?)");
    mysqli_stmt_bind_param($stmt,"ii",$NuserId1,$NuserId2);
    if(mysqli_stmt_execute($stmt)){
        mysqli_stmt_close($stmt);
        return true;
    }
    mysqli_stmt_close($stmt);
    return false;
}

function showNeighbor($conn,$NuserId1){
    $res = mysqli_query($conn,"select Name, Address,Userid from Neighbor, User where User.UserId = Neighbor.NuserId2 and NuserId1 = ".$NuserId1);
    return $res;
}

/*
 * functions for friends & friendwaitinglist
 */
function showFriend($conn, $UserId){
    $res = mysqli_query($conn,"select FUserId2 as fri from Friend where FUserId1 = ".$UserId." Union select FUserId1 as fri from Friend where FUserId2 = ".$UserId);
    return $res;
}

//be requested as a friend,so user here should be in FWUSERI2
function showFriendWaitingList($conn, $UserId){
    $res = mysqli_query($conn,"select * from FriendWaitingList Where FWUserId2 = ".$UserId);
    return $res;
}

function isFriends($conn,$UserId1,$UserId2){
    $stmt = mysqli_prepare($conn,"Select * from Friend where (FUserId1 = ? and FUserId2 = ? ) or (FUserId1 = ? and FUserId2 = ? )");
    mysqli_stmt_bind_param($stmt,"iiii",$UserId1,$UserId2,$UserId2,$UserId1);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $num = mysqli_stmt_num_rows($stmt);
    mysqli_stmt_free_result($stmt);
    mysqli_stmt_close($stmt);
    if($num > 0){return true;}
    return false;
}

function insertFriendWaitingList($conn,$UserId,$FriendId){
    if(isFriends($conn,$UserId,$FriendId)){ //friend already
        return false;
    }
    $stmt = mysqli_prepare($conn,"insert into FriendWaitingList VALUES (?,?,now())");
    mysqli_stmt_bind_param($stmt,"ii",$UserId,$FriendId);
    if(mysqli_stmt_execute($stmt)){
        mysqli_stmt_close($stmt);
        return true;
    }
    mysqli_stmt_close($stmt);
    return false;
}
// when insert friend a trigger will go to delete corresponding data in friendwaitinglist
function insertFriend($conn, $FUser1, $FUser2){
    if(isFriends($FUser1,$FUser2)){ //friend already
        return false;
    }
    $stmt = mysqli_prepare($conn,"insert into Friend VALUES (?,?)");
    mysqli_stmt_bind_param($stmt,"ii",$FUser1,$FUser2);
    if(mysqli_stmt_execute($stmt)){
        mysqli_stmt_close($stmt);
        return true;
    }
    mysqli_stmt_close($stmt);
    return false;
}

/*
 * functions for Member & WaitingList & ApproveList
 */

//check if the request exists
function isWaiting($conn,$UserId,$BlockId){
    $stmt = mysqli_prepare($conn,"select * from WaitingList where WUserid = ? and Blockid = ?");
    mysqli_stmt_bind_param($stmt,"ii",$UserId,$BlockId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $num = mysqli_stmt_num_rows($stmt);
    mysqli_stmt_free_result($stmt);
    mysqli_stmt_close($stmt);
    if($num > 0){return true;}
    return false;
}

function insertWaitingList($conn, $UserId, $BlockId){
    if (isWaiting($conn, $UserId, $BlockId)) {
        return false;
    }
    $stmt = mysqli_prepare($conn, "insert into WaitingList(WUserId, Requesttime,BlockId) VALUES (?,now(),?)");
    mysqli_stmt_bind_param($stmt,"ii",$UserId, $BlockId);
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        return true;
    }
    mysqli_stmt_close($stmt);
    return false;
}

//for the certain user, show all the join requests in his block
function showWaitingList($conn, $BlockId){
    $res = mysqli_query($conn, "Select * from WaitingList where BlockId = ".$BlockId);
    return $res;
}

//once approve, insert into approveList. need WaitingList's REQUESTID!
//all the others things like add to member table will be done by trigger
//just insert!
function insertApproveList($conn, $UserId, $RequestId){
    $stmt = mysqli_prepare($conn, "insert into ApproveList VALUES (?,?,now())");
    mysqli_stmt_bind_param($stmt,"ii",$RequestId, $UserId);
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        return true;
    }
    mysqli_stmt_close($stmt);
    return false;
}
/*
 *  functions for Messages & Thread
 */
function showSentMessage($conn, $UserId){
    $res = mysqli_query($conn,"select * from Message where postId = ".$UserId);
    return $res;
}

function showBlockMessage($conn,$BlockId){
    $res = mysqli_query($conn,"select MessageId, Subject, Title, Name, PostTime, PostId, Data from Message, Thread, User where VisibleType = 'block' and VisibleTo = ".$BlockId." and User.UserId = Message.PostId and Message.ThreadId = Thread.ThreadId");
    return $res;
}

function showHoodMessage($conn,$BlockId){
    $res = mysqli_query($conn,"select hoodid from Block where Blockid = ".$BlockId);
    $row = mysqli_fetch_array($res);
    $res = mysqli_query($conn, "SELECT MessageId, Subject, Title, Name, PostTime, PostId, Data FROM Message,User,Thread WHERE User.UserId=Message.PostId and Message.ThreadId = Thread.ThreadId and Thread.VisibleType='hood'and Thread.Visibleto=".$row[0]);
    return $res;
}

function showFriendMessage($conn, $UserId){
    $res = mysqli_query($conn, "select MessageId, Subject, Title, Name, PostTime, PostId, Data from Message, Thread, User where VisibleType = 'friend' and VisibleTo = ".$UserId." and User.UserId = Message.PostId and Message.ThreadId = Thread.ThreadId");
    return $res;
}

function showNeighborMessage($conn, $UserId){
    $res = mysqli_query($conn, "select * from Message NATURAL join Thread where VisibleType = 'neighbor' and VisibleTo = ".$UserId);
    return $res;
}

//return all the meaage in a thread
function showThreadMessage($conn,$ThreadId){
    $res = mysqli_query($conn,"select * from Message where ThreadId = ".$ThreadId);
    return $res;
}
//reply a single message
function replyMessage($conn, $UserId, $ThreadId, $MessageId, $Title, $Subject,$data,$Longitude,$Latitude){
    $stmt = mysqli_prepare($conn,"insert into Message(Subject, Title, Postid,PostTime,Longitude,Latitude,ReplyId, Data,ThreadId) VALUES
              (?,?,?,now(),?,?,?,?,? )");
    mysqli_stmt_bind_param($stmt,"ssiddssi",$Subject,$Title,$UserId,$Longitude,$Latitude,$MessageId,$data,$ThreadId);
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        return true;
    }
    mysqli_stmt_close($stmt);
    return false;
}
//reply a thread
function replyThread($conn, $UserId, $ThreadId, $Title, $Subject,$data,$Longitude,$Latitude)
{
    $stmt = mysqli_prepare($conn, "insert into Message(Subject, Title, Postid,PostTime,Longitude,Latitude, Data,ThreadId) VALUES
              (?,?,?,now(),?,?,?,? )");
    mysqli_stmt_bind_param($stmt, "ssiddsi", $Subject, $Title, $UserId, $Longitude, $Latitude, $data, $ThreadId);
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        return true;
    }
    mysqli_stmt_close($stmt);
    return false;
}
//create a thread
function insertThread($conn, $initialMessage,$Type, $Visibleto){
    $stmt = mysqli_prepare($conn,"insert into Thread (InitialMessage,VisibleType,VisibleTo) VALUES (? ,? ,?)");
    mysqli_stmt_bind_param($stmt,"sss",$initialMessage,$Type,$Visibleto);
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        return true;
    }
    mysqli_stmt_close($stmt);
    return false;
}

/*
 * other operations
 */

function sendMail($conn,$UserId){
    $res = mysqli_query($conn,"select email from user where UserId = ".$UserId);
    $row = mysqli_fetch_row($res);
    $email = $row[0];
    mail($email,"NEW Message","You got a new message");
}

/**
 * @param $conn
 * @param $UserId
 * @param $BlcokId
 */
function findNotFriend($conn, $UserId, $BlockId){
    $res = mysqli_query($conn, "select Hoodid from Block where BlockId = ".$BlockId);
    $row = mysqli_fetch_array($res);
    $res2 = mysqli_query($conn,"select Userid, Name, Address from User where blockid in (select blockid from Block where hoodid = ".$row[0].")
and userid not in (select FUserid2 as userid from friend where FUserId1 = ".$UserId.")
and userid not in (select Fuserid1 as userid from friend where Fuserid2 = ".$UserId.") and UserId !=".$UserId);
    return $res2;
}

//any user  can be only one member of a block.
function isMember($conn,$Userid){
    $res = mysqli_query($conn,"select MuserId from member where MuserId = ".$Userid);
    if(mysqli_num_rows($res)>0){
    return true;
    }
    return false;
}
function getHoodId($conn, $BlockId){
    $res = mysqli_query($conn,"Select Hoodid from Block where blockId = ".$BlockId);
    $row = mysqli_fetch_array($res);
    return $row[0];
}

function getMaxThread($conn){
    $res = mysqli_query($conn,"select threadId from THread ORDER by threadId DESC limit 1");
    $row = mysqli_fetch_array($res);
    return $row[0];
}

function findnotNeighbor($conn,$UserId,$BlockId){
    return $res = mysqli_query($conn,"select Userid, Name, Address from user where userid not in (select NUserid2 from Neighbor where NUserId1 = ".$UserId." ) and blockid = ".$BlockId);
}

function getaddress($address){
    // url encode the address
    $address = urlencode($address);

    // google map geocode api url
    $url = "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key=AIzaSyDxgb9HBTqkXFW1AQOJd8dbrtw41bJhOQk";

    // get the json response
    $resp_json = file_get_contents($url);

    // decode the json
    $resp = json_decode($resp_json, true);

    // response status will be 'OK', if able to geocode given address
    if($resp['status']=='OK'){

        $data_arr = array();
        foreach ($resp["results"] as $result) {
            foreach ($result["address_components"] as $address) {
                if (in_array("street_number", $address["types"])) {
                    array_push($data_arr,$address["long_name"]);
                }
            }
        }
        foreach ($resp["results"] as $result) {
            foreach ($result["address_components"] as $address) {
                if (in_array("route", $address["types"])) {
                    array_push($data_arr,$address["long_name"]);
                }
            }
        }
        foreach ($resp["results"] as $result) {
            foreach ($result["address_components"] as $address) {
                if (in_array("neighborhood", $address["types"])) {
                    array_push($data_arr,$address["long_name"]);
                }
            }
        }
        return $data_arr;
    }else{
        return false;
    }
}


function getBlockId($conn,$route,$neighborhood){
    $res = mysqli_query($conn, "select blockid from Block natural join Hood where route = '".$route."' and neighborhood = '".$neighborhood."'");
    $row = mysqli_fetch_array($res);
    return $row[0];
}

function isMoveout($conn,$route,$neighborhood,$block){
    if(getBlockId($conn,$route,$neighborhood) != $block){
    return true;
}
return false;
}
?>


