<?php
/**
 * Created by PhpStorm.
 * User: Neramit777
 * Date: 9/11/2017
 * Time: 11:48 AM
 */
// Set time zone to Bangkok in Asia ----------------------------------------------------------------------------------------------------------------------------------
date_default_timezone_set('Asia/Bangkok');

// Tell header for content-type is json format -----------------------------------------------------------------------------------------------------------------------
header('Content-Type: application/json');

// Declare variables -------------------------------------------------------------------------------------------------------------------------------------------------
$jsonR = $_SESSION['data'];
$displayName = $jsonR['displayName'];

$token = $_SESSION['token'];
$Data = new \stdClass();

// Check token -------------------------------------------------------------------------------------------------------------------------------------------------------
require_once('CheckToken.php');
if ($checkToken == 1) {
    $username = $name;
    require_once('DBconnect.php');
    // Get display name
    $sql = "UPDATE users SET displayName='$displayName' WHERE BINARY username='$username'";
    if ($con->query($sql) == TRUE) {
        $Data->status = 200;
        $Data->message = "Change displayName successful.";
    } else {
        $Data->status = 500;
        $Data->message = "Change fail.";
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