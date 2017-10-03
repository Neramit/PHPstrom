<?php
/**
 * Created by PhpStorm.
 * User: Neramit777
 * Date: 9/20/2017
 * Time: 10:51 AM
 */

// Set time zone to Bangkok in Asia ----------------------------------------------------------------------------------------------------------------------------------
date_default_timezone_set('Asia/Bangkok');

// Tell header for content-type is json format -----------------------------------------------------------------------------------------------------------------------
header('Content-Type: application/json');

// Declare variables -------------------------------------------------------------------------------------------------------------------------------------------------
$token = $_SESSION['token'];
$jsonR = $_SESSION['data'];
$groupImageURL = $jsonR['groupImageURL'];
$groupUID = $jsonR['groupUID'];

$Data = new \stdClass();
$data = new \stdClass();

require_once('CheckToken.php');
if ($checkToken == 1) {
    $username = $name;
    require_once('DBconnect.php');
    if ($groupImageURL != "null") {
        $sql = "UPDATE groups SET groupImageURL = '$groupImageURL' WHERE BINARY groupUID = '$groupUID'";
        if ($con->query($sql) == TRUE) {
            $Data->status = 200;
            $Data->message = "Create group successful";
        } else {
            $Data->status = 500;
            $Data->message = "Create group fail";
        }
    } else {
        $Data->status = 200;
        $Data->message = "Create group successful";
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