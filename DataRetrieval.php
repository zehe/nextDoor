<?php
/**
 * Created by PhpStorm.
 * User: bowang
 * Date: 12/17/15
 * Time: 20:42
 */
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
    $res = mysqli_query($conn,"select * from Neighbor where NuserId1 = ".$NuserId1);
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


?>


