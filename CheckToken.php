<?php
/**
 * Created by PhpStorm.
 * User: Neramit777
 * Date: 9/5/2017
 * Time: 4:19 PM
 */

// Report all errors except E_NOTICE
error_reporting(E_ALL & ~E_NOTICE);

// Set time zone to Bangkok in Asia ---------------------------------------------------------------------------------------------------------
date_default_timezone_set('Asia/Bangkok');

// Tell header for content-type is json format ----------------------------------------------------------------------------------------------
header('Content-Type: application/json');

// Declare variables ------------------------------------------------------------------------------------------------------------------------
$token = $_SESSION['token'];
$name = 'test';

// Get username from token reference --------------------------------------------------------------------------------------------------------
require_once("DBconnect.php");
$sql = "SELECT username FROM token_username WHERE BINARY token='$token'";
$query = mysqli_query($con, $sql);
$checkToken = 0;

if ($query->num_rows == 1) {
    $checkToken = 1;
    while ($row = mysqli_fetch_assoc($query)) {
        $name = $row["username"];
    }
} else
    $name = null;

// Close table DB & session -----------------------------------------------------------------------------------------------------------------
//mysqli_close($con);
//session_write_close();