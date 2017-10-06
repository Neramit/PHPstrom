<?php
/**
 * Created by PhpStorm.
 * User: Neramit777
 * Date: 10/5/2017
 * Time: 4:49 PM
 */

// Set time zone to Bangkok in Asia ----------------------------------------------------------------------------------------------------------------------------------
date_default_timezone_set('Asia/Bangkok');

// Tell header for content-type is json format -----------------------------------------------------------------------------------------------------------------------
header('Content-Type: application/json');

// Declare variables -------------------------------------------------------------------------------------------------------------------------------------------------
$token = $_SESSION['token'];
$jsonR = $_SESSION['data'];
$groupUID = $jsonR['genNum'];
$a = array();
$b = array();

$Data = new \stdClass();
$data = new \stdClass();

require_once('CheckToken.php');
if ($checkToken == 1) {
    $username = $name;

    require_once('DBconnect.php');
    $sql = "SELECT * FROM group_member INNER JOIN users ON group_member.memberUsername = users.username WHERE BINARY groupUID = '$groupUID'";
    $query = mysqli_query($con, $sql);
    if ($query) {
        if (mysqli_num_rows($query) > 0) {
            while ($check = mysqli_fetch_assoc($query)) {
                $item['memberUsername'] = $check['memberUsername'];
                $item['memberStatus'] = $check['memberStatus'];
                $item['memberDisplayName'] = $check['displayName'];
                $item['memberImageURL'] = $check['displayPictureURL'];
                array_push($a, $item);
            }

            $jsonObject2['memberList'] = $a;
            $sql = "SELECT * FROM groups WHERE BINARY groupUID = '$groupUID'";
            $query = mysqli_query($con, $sql);
            if ($query) {
                if (mysqli_num_rows($query) == 1) {
                    while ($check = mysqli_fetch_assoc($query)) {
                        $item2['groupOwner'] = $check['groupOwner'];
                        $item2['groupName'] = $check['groupName'];
                        $item2['groupPassword'] = $check['groupPassword'];
                        $item2['groupImageURL'] = $check['groupImageURL'];
                        $item2['groupMemberNum'] = $check['groupMemberNum'];
                        $jsonObject2['groupDetail'] = $item2;
                    }
                }
            }

            // Combine to $Data
            $jsonObject['data'] = $jsonObject2;
            $jsonObject['status'] = 200;
            $jsonObject['message'] = "Get data successful.";
            $Data = $jsonObject;
        } else {
            $jsonObject['status'] = 201;
            $jsonObject['message'] = "Not have data1.";
            $Data = $jsonObject;
        }
    } else {
        $jsonObject['status'] = 500;
        $jsonObject['message'] = "Not have data2.";
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