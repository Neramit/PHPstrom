<?php
/**
 * Created by Atom-Text Editor.
 * User: Neramit777
 * Date: 8/31/2017
 * Time: 15:17 PM
 */

// Set time zone to Bangkok in Asia --------------------------------------------------------------------------------------------------------
date_default_timezone_set('Asia/Bangkok');

// Define variable for project -------------------------------------------------------------------------------------------------------------
$jsonR = $_SESSION['data'];
$email = $jsonR['email'];
$password = $jsonR['password'];
$salt = "a059a744729dfc7a4b4845109f591029";
$Data = new \stdClass();

$password = md5($salt . $password);
// Tell header for content-type is json format ---------------------------------------------------------------------------------------------
header('Content-Type: application/json');

// Update password -------------------------------------------------------------------------------------------------------------------------
require_once('DBconnect.php');
$sql = "UPDATE users SET password = '$password' WHERE email='$email'";
if ($con->query($sql) == TRUE) {
    $Data->status = 200;
    $Data->message = "Password has changed";
} else {
    $Data->status = 501;
    $Data->message = "Error change password ";
}
// Retrieve value json format to client --------------------------------------------------------------------------------------------------
$retrieve_json = json_encode($Data);
echo $retrieve_json;
// Close table DB & session -----------------------------------------------------------------------------------------------------------------
mysqli_close($con);
session_write_close();

?>
