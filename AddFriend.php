<?php
/**
 * Created by PhpStorm.
 * User: Neramit777
 * Date: 9/4/2017
 * Time: 12:04 PM
 */

// Report all errors except E_NOTICE
error_reporting(E_ALL & ~E_NOTICE);

// Set time zone to Bangkok in Asia ----------------------------------------------------------------------------------------------------------------------------------
date_default_timezone_set('Asia/Bangkok');

// Tell header for content-type is json format -----------------------------------------------------------------------------------------------------------------------
header('Content-Type: application/json');

// Declare variables -------------------------------------------------------------------------------------------------------------------------------------------------
$jsonR = $_SESSION['data'];
$addUsername = $jsonR['username'];

$token = $_SESSION['token'];
$Data = new \stdClass();

// Check token -------------------------------------------------------------------------------------------------------------------------------------------------------
require_once('CheckToken.php');
if ($checkToken == 1) {
    $username = $name;
    // check add yourself --------------------------------------------------------------------------------------------------------------------------------------------
    if ($addUsername == $username) {
        $Data->status = 401;
        $Data->message = "You can't add yourself as a friend.";
    } else {
        require_once('DBconnect.php');
        // Check addUsername have ?
        $sql = "SELECT username FROM users WHERE BINARY username = '$addUsername'";
        $query = mysqli_query($con, $sql);
        if ($query->num_rows == 1) {
            // Check that ever Friend or not ? -------------------------------------------------------------------------------------------------------------------------------
            $sql = "SELECT ownerUsername,friendUsername,friendStatus FROM friends WHERE BINARY ownerUsername='$username' AND BINARY friendUsername = '$addUsername'";
            $query = mysqli_query($con, $sql);
            if ($query->num_rows == 1) {
                while ($row = mysqli_fetch_assoc($query)) {
                    if ($row['friendStatus'] == 0) {
                        $Data->status = 201;
                        $Data->message = "You have already request this friend.";
                    } elseif ($row['friendStatus'] == 1) {
                        $Data->status = 202;
                        $Data->message = "You have already are friend.";
                    }
                }
            } else {
                $sql = "SELECT ownerUsername, friendUsername, friendStatus FROM friends WHERE BINARY ownerUsername = '$addUsername' AND BINARY friendUsername = '$username'";
                $query = mysqli_query($con, $sql);
                if ($query->num_rows == 1) {
                    while ($row = mysqli_fetch_assoc($query)) {
                        if ($row['friendStatus'] == 0) {
                            $sql = "UPDATE friends SET friendStatus = 1 WHERE BINARY friendUsername = '$username' AND BINARY ownerUsername = '$addUsername'";
                            if ($con->query($sql) == TRUE) {
                                $Data->status = 203;
                                $Data->message = "You become friend now.";
                            } else {
                                $Data->status = 500;
                                $Data->message = "Request this user fail.";
                            }
                        } elseif ($row['friendStatus'] == 1) {
                            $Data->status = 202;
                            $Data->message = "You have already are friend.";
                        }
                    }
                } else {
                    $sql = "INSERT INTO friends (ownerUsername,friendUsername) VALUES ('$username','$addUsername')";
                    // Check for query succeed? ----------------------------------------------------------------------------------------------------------------------------------
                    if (mysqli_query($con, $sql)) {
                        $Data->status = 200;
                        $Data->message = "Request this user successful.";
                    } else {
                        $Data->status = 500;
                        $Data->message = "Request this user fail.";
                    }
                }
            }
        } else {
            $Data->status = 402;
            $Data->message = "Not have this friend.";
        }
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