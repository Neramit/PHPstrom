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
$registrationID = $jsonR['registrationID'];
//$password = $jsonR['password'];
/** Password with salt **/
$Data = new \stdClass();
$data = new \stdClass();

// Tell header for content-type is json format ----------------------------------------------------------------------------------------------
header('Content-Type: application/json');

// Check empty field ------------------------------------------------------------------------------------------------------------------------
if ($username == '' || $password == '') {
    $Data->status = 400;
    $Data->message = "Please fill all field";
} else { // Get data from DB table ----------------------------------------------------------------------------------------------------------
    require_once('DBconnect.php');
    $sql = "SELECT isDelete FROM users WHERE BINARY username='$username'";
    $query = mysqli_query($con, $sql);
    if ($query->num_rows == 1) {
        while ($check = mysqli_fetch_assoc($query)) {
            if ($check['isDelete']) {
                $Data->status = 404;
                $Data->message = "This account has been delete";
            } else {
                $sql = "SELECT * FROM users WHERE BINARY username='$username' AND BINARY password='$password'";
                $query = mysqli_query($con, $sql);
                if ($query->num_rows == 1) {
                    // Check already have in DB -------------------------------------------------------------------------------------------------------------
                    $sql = "SELECT * FROM token_username WHERE BINARY username='$username'";
                    $check = mysqli_fetch_array(mysqli_query($con, $sql));
                    if (isset($check)) {
                        $date = new DateTime();
                        $timeStamp = $date->getTimestamp();
                        $token = md5($timeStamp . $username);                                        // Token = MD5(Timestamp + username)

                        $sql = "UPDATE token_username SET token = '$token',registrationID = '$registrationID',logged = '$timeStamp' WHERE BINARY username='$username'";
                        if ($con->query($sql) == TRUE) {
                            $Data->status = 200;
                            $Data->message = "Logged in!";
                            $Data->data = $data;
                        } else {
                            $Data->status = 402;
                            $Data->message = "Login fail";
                        }
                    } else {
                        $date = new DateTime();
                        $token = md5($date->getTimestamp() . $username);
                        $sql = "INSERT INTO token_username (username,token,registrationID,logged) VALUES ('$username','$token','$registrationID','$timeStamp')";
                        // Check for query succeed? -----------------------------------------------------------------------------------------------------
                        if (mysqli_query($con, $sql)) {
                            $Data->status = 200;
                            $Data->message = "Logged in!";
                        } else {
                            $Data->status = 402;
                            $Data->message = "Login fail";
                        }
                    }
                    $check = mysqli_fetch_assoc($query);
                    $data->token = $token;
                    $data->username = $username;
                    $data->email = $check["email"];
                    $data->displayName = $check["displayName"];
                    $data->displayPictureURL = $check["displayPictureURL"];
                    $Data->data = $data;
                } else {
                    $Data->status = 401;
                    $Data->message = "Wrong username or password";
                }
            }
        }
    } else {
        $Data->status = 403;
        $Data->message = "Don't have this user";
    }
}

// Retieve value json format to client --------------------------------------------------------------------------------------------------
$retrieve_json = json_encode($Data);
echo $retrieve_json;

// Close table DB & session -----------------------------------------------------------------------------------------------------------------
mysqli_close($con);
session_write_close();