<?php
/**
 * Created by PhpStorm.
 * User: Neramit777
 * Date: 10/2/2017
 * Time: 5:20 PM
 */

// Set time zone to Bangkok in Asia ---------------------------------------------------------------------------------------------------------
date_default_timezone_set('Asia/Bangkok');

// Tell header for content-type is json format ----------------------------------------------------------------------------------------------
header('Content-Type: application/json');

// Declare variables ------------------------------------------------------------------------------------------------------------------------
$token = $_SESSION['token'];
$Data = new stdClass();
$data = new stdClass();

require_once('CheckToken.php');
if ($checkToken == 1) {
    $username = $name;
    require_once('DBconnect.php');
    // You request add friend ------------------------------------------------------------------------------------------------------------------
    $sql = "SELECT * FROM group_member INNER JOIN groups ON group_member.groupUID = groups.groupUID WHERE BINARY group_member.memberUsername = '$username' OR groups.groupOwner = '$username'";
    $query = mysqli_query($con, $sql);
    $a = array();
    if ($query) {
        if (mysqli_num_rows($query) > 0) {
            while ($check = mysqli_fetch_assoc($query)) {
                if ($check['groupOwner'] == $username) {
                    $item['groupStatus'] = 1;
                } else
                    $item['groupStatus'] = $check['memberStatus'];
                $item['groupUID'] = $check['groupUID'];
                $item['groupName'] = $check['groupName'];
                $item['groupImageURL'] = $check['groupImageURL'];
                $item['groupMemberNum'] = $check['groupMemberNum'];
                array_push($a, $item);
            }// Combine to $Data
            $jsonObject['data'] = $a;
            $jsonObject['status'] = 200;
            $jsonObject['message'] = "Get data successful.";
            $Data = $jsonObject;
        } else {
            $jsonObject['status'] = 201;
            $jsonObject['message'] = "Not have data1.";
            $Data = $jsonObject;
        }
    } else {
        $jsonObject['status'] = 201;
        $jsonObject['message'] = "Not have data2." . mysqli_error($con);
        $Data = $jsonObject;
    }
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