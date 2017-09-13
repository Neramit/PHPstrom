<?php
/**
 * Created by PhpStorm.
 * User: Neramit777
 * Date: 8/24/2017
 * Time: 3:19 PM
 */

// Set time zone to Bangkok in Asia ---------------------------------------------------------------------------------------------------------
date_default_timezone_set('Asia/Bangkok');

// Define variable for project --------------------------------------------------------------------------------------------------------------
$jsonR = $_SESSION['data'];
$username = $jsonR['username'];
$password = $jsonR['password'];
/** Password with salt **/
$salt = "a059a744729dfc7a4b4845109f591029";
$Data = new \stdClass();

// Tell header for content-type is json format ----------------------------------------------------------------------------------------------
header('Content-Type: application/json');

// Check empty field ------------------------------------------------------------------------------------------------------------------------
if ($username == '' || $password == '') {
    $Data->status = 400;
    $Data->message = "Please fill all field";
} else { // Get data from DB table ----------------------------------------------------------------------------------------------------------
    require_once('DBconnect.php');
    $sql = "SELECT * FROM users WHERE BINARY username='$username' AND BINARY password='$password'";
    $check = mysqli_fetch_array(mysqli_query($con, $sql));

    // Check already have in DB -------------------------------------------------------------------------------------------------------------
    if (isset($check)) {
        $sql = "SELECT * FROM token_username WHERE BINARY username='$username'";
        $check = mysqli_fetch_array(mysqli_query($con, $sql));
        if (isset($check)) {
            $date = new DateTime();
            $token = md5($date->getTimestamp() . $username);                                        // Token = MD5(Timestamp + username)
            $sql = "UPDATE token_username SET token = '$token' WHERE BINARY username='$username'";
            if ($con->query($sql) == TRUE) {
                $Data->status = 200;
                $Data->message = "Logged in!";
                $Data->data = $token;
            } else {
                $Data->status = 401;
                $Data->message = "Login fail";
            }
        } else {
            $date = new DateTime();
            $token = md5($date->getTimestamp() . $username);
            $sql = "INSERT INTO token_username (username,token) VALUES('$username','$token')";
            // Check for query succeed? -----------------------------------------------------------------------------------------------------
            if (mysqli_query($con, $sql)) {
                $Data->status = 200;
                $Data->message = "Logged in!";
                $Data->data = $token;
            } else {
                $Data->status = 401;
                $Data->message = "Login fail";
            }
        }
    } else {
        $Data->status = 401;
        $Data->message = "Wrong username or password";
    }
    // Retieve value json format to client --------------------------------------------------------------------------------------------------
    $retrieve_json = json_encode($Data);
    echo $retrieve_json;

    // Close table DB & session -----------------------------------------------------------------------------------------------------------------
    mysqli_close($con);
    session_write_close();
}
