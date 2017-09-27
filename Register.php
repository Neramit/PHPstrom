<?php
/**
 * Created by PhpStorm.
 * User: Neramit777
 * Date: 8/11/2017
 * Time: 11:11 AM
 */
// Set time zone to Bangkok in Asia ---------------------------------------------------------------------------------------------------------
date_default_timezone_set('Asia/Bangkok');

// Define variable for project --------------------------------------------------------------------------------------------------------------
$jsonR = $_SESSION['data'];
$username = $jsonR['username'];
$email = $jsonR['email'];
$password = $jsonR['password'];
/** Password with salt **/

$salt = "a059a744729dfc7a4b4845109f591029";
$Data = new \stdClass();

// Tell header for content-type is json format ----------------------------------------------------------------------------------------------
header('Content-Type: application/json');

// Check empty field ------------------------------------------------------------------------------------------------------------------------// Get data from DB table -------------------------------------------------------------------------------------------------------------
require_once('DBconnect.php');
$sql = "SELECT * FROM users WHERE BINARY username='$username' OR BINARY email='$email'";
$check = mysqli_fetch_array(mysqli_query($con, $sql));

// Check already exist ------------------------------------------------------------------------------------------------------------------
if (isset($check)) {
    $Data->status = 401;
    $Data->message = "Already register.";
} else {  // Input values to database -----------------------------------------------------------------------------------------------------
    $password = md5($salt . $password);
    $sql = "INSERT INTO users (username,password,email,displayName) VALUES ('$username','$password','$email','$username')";

    // Check for query succeed? ---------------------------------------------------------------------------------------------------------
    if (mysqli_query($con, $sql)) {
        $Data->status = 200;
        $Data->message = "Register successful.";
    } else {
        $Data->status = 500;
        $Data->message = "Error: " . $con->error;
    }
}

// Retrieve value json format to client --------------------------------------------------------------------------------------------------
$retrieve_json = json_encode($Data);
echo $retrieve_json;

// Close table DB & session -----------------------------------------------------------------------------------------------------------------
mysqli_close($con);
session_write_close();