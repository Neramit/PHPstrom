<?php
/**
 * Created by PhpStorm.
 * User: Neramit777
 * Date: 9/27/2017
 * Time: 9:07 AM
 */

// Set time zone to Bangkok in Asia ---------------------------------------------------------------------------------------------------------
date_default_timezone_set('Asia/Bangkok');

// Tell header for content-type is json format ----------------------------------------------------------------------------------------------
header('Content-Type: application/json');

// Declare variables -------------------------------------------------------------------------------------------------------------------------------------------------
$token = $_SESSION['token'];
$jsonR = $_SESSION['data'];
$registrationID = $jsonR['displayPictureURL'];

$Data = new \stdClass();
$data = new \stdClass();

require_once('CheckToken.php');
if ($checkToken == 1) {
    $username = $name;
    require_once('DBconnect.php');
    $sql = "SELECT * FROM token_username WHERE token='$token'";
    $check = mysqli_fetch_array(mysqli_query($con, $sql));
    if (isset($check)) {
        $sql = "UPDATE token_username SET registrationID = '$registrationID' WHERE token='$token'";
        if ($con->query($sql) == TRUE) {
            $Data->status = 200;
            $Data->message = "Update registrationID successful.";
        } else {
            $Data->status = 500;
            $Data->message = "Update registrationID fail.";
        }
    }
} else {
    $Data->status = 400;
    $Data->message = "Wrong token.";
}

// Retieve value json format to client ------------------------------------------------------------------------------------------------------
$retrieve_json = json_encode($Data);
echo $retrieve_json;

// Close table DB & session -----------------------------------------------------------------------------------------------------------------
mysqli_close($con);
session_write_close();