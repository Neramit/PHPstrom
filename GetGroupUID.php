<?php
/**
 * Created by PhpStorm.
 * User: Neramit777
 * Date: 10/2/2017
 * Time: 2:50 PM
 */

// Set time zone to Bangkok in Asia ----------------------------------------------------------------------------------------------------------------------------------
date_default_timezone_set('Asia/Bangkok');

// Tell header for content-type is json format -----------------------------------------------------------------------------------------------------------------------
header('Content-Type: application/json');

// Declare variables -------------------------------------------------------------------------------------------------------------------------------------------------
$token = $_SESSION['token'];
$jsonR = $_SESSION['data'];
$groupName = $jsonR['groupName'];
$groupOwner = $jsonR['groupOwner'];
$groupPassword = $jsonR['groupPassword'];
$groupMember = $jsonR['groupMember'];
$groupMemberNum = $jsonR['groupMemberNum'];

$Data = new \stdClass();
$data = new \stdClass();

// Check token ----------------------------------------------------------------------------------------------------------------------------------
require_once('CheckToken.php');
if ($checkToken == 1) {
    $username = $name;
    require_once('DBconnect.php');
    $sql = "INSERT INTO groups (groupName,groupOwner,groupPassword,groupMemberNum) VALUES ('$groupName','$groupOwner','$groupPassword','$groupMemberNum')";
    // Create group success ? -------------------------------------------------------------------------------------------------------------------
    if (mysqli_query($con, $sql)) {
        $sql = "SELECT groupUID FROM groups WHERE BINARY groupPassword = '$groupPassword' AND BINARY groupName = '$groupName'";
        $query = mysqli_query($con, $sql);
        if (mysqli_num_rows($query) == 1) {
            while ($row = mysqli_fetch_assoc($query)) {
                $data->groupUID = $row['groupUID'];
                // Run insert member of group ---------------------------------------------------------------------------------------------------
                $i = 1;  // --- Var '$i' for count not add first member ---//
                foreach ($groupMember as $key => $val) {
                    $friendUsername = $val['friendUsername'];
                    $sql = "INSERT INTO group_member (memberUsername,groupUID) VALUES ('$friendUsername','$data->groupUID')";
                    if ($i!=1) {
                        mysqli_query($con, $sql);
//                        if (mysqli_query($con, $sql))
//                            print_r("Success -> ".$con->error);
//                        else
//                            print_r("Fail -> ".$con->error);
                    }
                    $i++;
                }
            }
            $Data->data = $data;
            $Data->status = 200;
            $Data->message = "Successful";
        } else {
            $Data->status = 500;
            $Data->message = "Failed get data";
            print_r($con->error);
        }
    } else {
        $Data->status = 500;
        $Data->message = "Failed insert data";
        print_r($con->error);
    }
} else {
    $Data->status = 400;
    $Data->message = "Wrong token.";
}

// Retrieve value json format to client ------------------------------------------------------------------------------------------------------------------------------
$retrieve_json = json_encode($Data);
echo $retrieve_json;

// Close table DB & session ------------------------------------------------------------------------------------------------------------------------------------------
mysqli_close($con);
session_write_close();