<?php
/**
 * Created by PhpStorm.
 * User: Neramit777
 * Date: 9/4/2017
 * Time: 2:45 PM
 */

// Set time zone to Bangkok in Asia ---------------------------------------------------------------------------------------------------------
date_default_timezone_set('Asia/Bangkok');

// Tell header for content-type is json format ----------------------------------------------------------------------------------------------
header('Content-Type: application/json');

// Declare variables ------------------------------------------------------------------------------------------------------------------------
$token = $_SESSION['token'];
$Data = new stdClass();
$data = new \stdClass();
$a = array();
$i = 0;

require_once('CheckToken.php');
if ($checkToken == 1) {
    $username = $name;
    require_once('DBconnect.php');
    $sql = "SELECT * FROM friends INNER JOIN users ON friends.friendUsername=users.username";
//    $sql = "SELECT * FROM friends INNER JOIN Customers ON Orders.CustomerID=Customers.CustomerID WHERE BINARY ownerUsername = '$username' OR BINARY friendUsername = '$username'";

    $query = mysqli_query($con, $sql);
    if ($query->num_rows > 0) {
        while ($check = $query->fetch_assoc()) {
            $data->ownerUsername = $check["ownerUsername"];
            $data->friendUsername = $check["friendUsername"];
            $data->friendStatus = $check["friendStatus"];
            $data->isFavorite = $check["isFavorite"];
            $data->chatroomUID = $check["chatroomUID"];
            $data->displayName = $check["displayName"];
            $data->displayPictureURL = $check["displayPictureURL"];
//            array_push($a,$data);
            $a[$i] = $data;
            $i++;
        }
        $Data->status = 200;
        $Data->message = "Successful.";
        $Data->data = $a;
    }else{
        $Data->status = 201;
        $Data->message = "Don't have friend.";
    }
}else{
    $Data->status = 400;
    $Data->message = "Wrong token.";
}

// Retrieve value json format to client ------------------------------------------------------------------------------------------------------------------------------
$retrieve_json = json_encode($Data);
echo $retrieve_json;

// Close table DB ---------------------------------------------------------------------------------------------------------------------------
mysqli_close($con);
session_write_close();