<?php
/**
 * Created by PhpStorm.
 * User: Neramit777
 * Date: 9/19/2017
 * Time: 9:12 AM
 */

// Set time zone to Bangkok in Asia ----------------------------------------------------------------------------------------------------------------------------------
date_default_timezone_set('Asia/Bangkok');

// Tell header for content-type is json format -----------------------------------------------------------------------------------------------------------------------
header('Content-Type: application/json');

// Declare variables -------------------------------------------------------------------------------------------------------------------------------------------------
$token = $_SESSION['token'];

$Data = new \stdClass();
$data = new \stdClass();

require_once ('CheckToken.php');
if ($checkToken == 1) {
    $username = $name;
    require_once ('DBconnect.php');
    $sql = "DELETE FROM friends WHERE BINARY ownerUserName = '$username' OR BINARY friendUserName = '$username'";
    if (mysqli_query($con, $sql)) {
        $sql = "DELETE FROM token_username WHERE BINARY username = '$username'";
        if (mysqli_query($con, $sql)) {
            $date = new DateTime();
            $Date=$date->getTimestamp();
            $sql = "UPDATE users SET isDelete = true,deleteTime = '$Date' WHERE BINARY username = '$username'";
            if ($con->query($sql) == TRUE) {
                $Data->status = 200;
                $Data->message = "Create group successful";
            } else {
                $Data->status = 500;
                $Data->message = "Create group fail";
            }
            $Data->status = 200;
            $Data->message = "Delete successful.";
        }
    }else{
        $Data->status = 500;
        $Data->message = "Delete failed.";
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