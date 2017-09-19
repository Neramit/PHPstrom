<?php
/**
 * Created by PhpStorm.
 * User: Neramit777
 * Date: 9/18/2017
 * Time: 1:29 PM
 */

// Set time zone to Bangkok in Asia ----------------------------------------------------------------------------------------------------------------------------------
date_default_timezone_set('Asia/Bangkok');

// Tell header for content-type is json format -----------------------------------------------------------------------------------------------------------------------
header('Content-Type: application/json');

// Declare variables -------------------------------------------------------------------------------------------------------------------------------------------------
$jsonR = $_SESSION['data'];
$displayPictureURL = $jsonR['displayPictureURL'];

$token = $_SESSION['token'];
$Data = new \stdClass();
$data = new \stdClass();

// Check token -------------------------------------------------------------------------------------------------------------------------------------------------------
require_once('CheckToken.php');
if ($checkToken == 1) {
    $username = $name;
    require_once('DBconnect.php');
    // Get display name
    $sql = "UPDATE users SET displayPictureURL = '$displayPictureURL' WHERE username = '$username'";
    if ($con->query($sql) == TRUE) {
        $Data->status = 200;
        $Data->message = "Change displayPicture successful.";
        $data->displayPictureURL = $displayPictureURL;
    } else {
        $Data->status = 500;
        $Data->message = "Change displayPicture fail.";
    }
    $Data->data = $data;
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
