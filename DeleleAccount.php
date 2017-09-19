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
$jsonR = $_SESSION['data'];
$displayPictureURL = $jsonR['displayPictureURL'];

$token = $_SESSION['token'];
$Data = new \stdClass();
$data = new \stdClass();





// Retrieve value json format to client ------------------------------------------------------------------------------------------------------------------------------
$retrieve_json = json_encode($Data);
echo $retrieve_json;

// Close table DB & session ------------------------------------------------------------------------------------------------------------------------------------------
mysqli_close($con);
session_write_close();