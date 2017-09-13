<?php
/**
 * Created by PhpStorm.
 * User: Neramit777
 * Date: 9/5/2017
 * Time: 1:25 PM
 */

// Set time zone to Bangkok in Asia ----------------------------------------------------------------------------------------------------------------------------------
date_default_timezone_set('Asia/Bangkok');

// Tell header for content-type is json format -----------------------------------------------------------------------------------------------------------------------
header('Content-Type: application/json');

// Declare variables -------------------------------------------------------------------------------------------------------------------------------------------------
$jsonR = $_SESSION['data'];
$searchUsername = $jsonR['username'];
$token = $_SESSION['token'];
$Data = new \stdClass();
$data = new \stdClass();

//Check Token --------------------------------------------------------------------------------------------------------------------------------------------------------
require_once('CheckToken.php');
if ($checkToken == 1) {
    $username = $name;
    // check add yourself --------------------------------------------------------------------------------------------------------------------------------------------
    if ($searchUsername == $username) {
        $Data->status = 401;
        $Data->message = "You can't add yourself as a friend.";
    } else {
        // Get data --------------------------------------------------------------------------------------------------------------------------------------------------
        require_once('DBconnect.php');
        $sql = "SELECT ownerUsername,friendUsername,friendStatus FROM friends WHERE ownerUsername = '$username' OR friendUsername = '$username'";
        $query = mysqli_query($con, $sql);
        // Check if
        if (mysqli_num_rows($query) >= 1) {
            while ($row = mysqli_fetch_assoc($query)) {
                if ($row['ownerUsername'] == $username && $row['friendUsername'] == $searchUsername) {
                    if ($row['friendStatus'] == 0) {
                        $Data->status = 201;   // TODO:Can't add friend
                        $Data->message = "You have already request to this user\nPlease wait for this user accept request.";
                    } else if ($row['friendStatus'] == 1) {
                        $Data->status = 200;   // TODO:Are friend
                        $Data->message = "You are friend";
                    }
                } elseif ($row['ownerUsername'] == $searchUsername && $row['friendUsername'] == $username) {
                    if ($row['friendStatus'] == 0) {
                        $Data->status = 201;   // Add friend immidiate
                        $Data->message = "This user have already request to add you\nIf you add this user you will become friend.";
                    } else if ($row['friendStatus'] == 1) {
                        $Data->status = 200;   // TODO:Are friend
                        $Data->message = "You are friend";
                    }
                } else {
                    $Data->status = 203;       // Can add friend
                    $Data->message = "Normally,Never have contact with this user.";
                }
            }
        } else {
            $Data->status = 202;               // Can add friend
            $Data->message = "Wow,Never have friend.";
        }

        // Get image URL
        $sql = "SELECT displayName,displayPictureURL FROM users WHERE BINARY username = '$searchUsername'";
        $query = mysqli_query($con, $sql);
        if (mysqli_num_rows($query) == 1) {
            while ($row = mysqli_fetch_assoc($query)) {
                if ($row["displayName"] == null) {
                    $data->displayName = $searchUsername;
                } else
                    $data->displayName = $row["displayName"];
                $data->displayPictureURL = $row["displayPictureURL"];
                $Data->data = $data;
            }
            // Not have username -------------------------------------------------------------------------------------------------------------------------------------
        } else {
            $Data->status = 402;
            $Data->message = "Not have this user.";
        }
    }
// Wrong token ------------------------------------------------------------------------------------------------------------------------------------------------------
} else {
    $Data->status = 400;
    $Data->message = "Wrong token.";
}

// Retrieve value json format to client -----------------------------------------------------------------------------------------------------------------------------
$retrieve_json = json_encode($Data);
echo $retrieve_json;

// Close table DB & session ------------------------------------------------------------------------------------------------------------------------------------------
mysqli_close($con);
session_write_close();
