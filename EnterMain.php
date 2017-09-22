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
$data = new stdClass();
$i = 0;

require_once('CheckToken.php');
if ($checkToken == 1) {
    $username = $name;
    require_once('DBconnect.php');
    // You request add friend ------------------------------------------------------------------------------------------------------------------
    $sql = "SELECT * FROM friends INNER JOIN users ON friends.friendUsername=users.username WHERE ownerUsername = '$username'";
    $query = mysqli_query($con, $sql);
    $a = array();
    if ($query) {
        if (mysqli_num_rows($query) > 0) {
            $index = 0;
            while ($check = mysqli_fetch_assoc($query)) {
//            $data->ownerUsername = $check['ownerUsername'];
//            $data->friendUsername = $check['friendUsername'];
//            $data->friendStatus = $check['friendStatus'];
//            $data->isFavorite = $check['isFavorite'];
//            $data->chatroomUID = $check['chatroomUID'];
//            $data->displayName = $check['displayName'];
//            $data->displayPictureURL = $check['displayPictureURL'];
                $item['ownerUsername'] = $check['ownerUsername'];
                $item['friendUsername'] = $check['friendUsername'];
                $item['friendStatus'] = $check['friendStatus'];
                $item['isFavorite'] = $check['isFavorite'];
                $item['chatroomUID'] = $check['chatroomUID'];
//            $item['displayName'] = isset($check['displayName']) ? null : $check['displayName'];
                $item['displayName'] = $check['displayName'];
                $item['displayPictureURL'] = $check['displayPictureURL'];

                array_push($a, $item);
//                print_r($a);
            }
        } else {
            $Data->status = 201;
            $Data->message = "Don't have friend.";
        }
    } else {
        $Data->status = 500;
        $Data->message = "Query error.";
    }
// Friend request add you ------------------------------------------------------------------------------------------------------------
    $sql = "SELECT * FROM friends INNER JOIN users ON friends.ownerUsername=users.username WHERE friendUsername = '$username'";
    $query = mysqli_query($con, $sql);
    if ($query) {
        if (mysqli_num_rows($query) > 0) {
//            $a = array();
            $index = 0;
            while ($check = mysqli_fetch_assoc($query)) {
                $item['ownerUsername'] = $check['ownerUsername'];
                $item['friendUsername'] = $check['friendUsername'];
                $item['friendStatus'] = $check['friendStatus'];
                $item['isFavorite'] = $check['isFavorite'];
                $item['chatroomUID'] = $check['chatroomUID'];
//            $item['displayName'] = isset($check['displayName']) ? null : $check['displayName'];
                $item['displayName'] = $check['displayName'];
                $item['displayPictureURL'] = $check['displayPictureURL'];
                array_push($a, $item);
//                        print_r($a);
            }
        } else {
            $Data->status = 201;
            $Data->message = "Don't have friend.";
        }
    } else {
        $Data->status = 500;
        $Data->message = "Query error.";
    }
    // Combine to $Data
    $jsonObject['data'] = $a;
    $jsonObject['status'] = 200;
    $jsonObject['message'] = "Successful.";
    $Data = $jsonObject;
} else {
    $Data->status = 400;
    $Data->message = "Wrong token.";
}

// Retrieve value json format to client --------------------------------------------------------------------------------------------------------
$retrieve_json = json_encode($Data);
echo $retrieve_json;

// Close table DB ------------------------------------------------------------------------------------------------------------------------------
mysqli_close($con);
session_write_close();