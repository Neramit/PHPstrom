<?php
/**
 * Created by PhpStorm.
 * User: Neramit777
 * Date: 8/31/2017
 * Time: 10:30 AM
 */

// Set time zone to Bangkok in Asia ---------------------------------------------------------------------------------------------------------
date_default_timezone_set('Asia/Bangkok');

// Tell header for content-type is json format ----------------------------------------------------------------------------------------------
header('Content-Type: application/json');

// Declare variables ------------------------------------------------------------------------------------------------------------------------
$jsonR = $_SESSION['data'];
$email = $jsonR['email'];
$genNumClient = $jsonR['genNum'];
$genNumServer = 0000000;
$TimeToExpired = 0000000000;
$Data = new \stdClass();

// Connect DB -------------------------------------------------------------------------------------------------------------------------------
require_once('DBconnect.php');

// Check time expired -----------------------------------------------------------------------------------------------------------------------
$sql = "SELECT timeToExpired FROM gennum_expire_and_email WHERE email='$email'";
$query = mysqli_query($con, $sql);

if(mysqli_num_rows($query)>0){
    while($row = mysqli_fetch_assoc($query)) {
        $TimeToExpired = $row["timeToExpired"];
    }
}
$date = new DateTime();
if($date->getTimestamp() < $TimeToExpired){
    $Data->status = 200;
    $Data->message = "Successful";
}else{
    $Data->status = 500;
    $Data->message = "Time out\nPlease enter email again";
}

// Check genNum -----------------------------------------------------------------------------------------------------------------------------
$sql = "SELECT genNum FROM gennum_expire_and_email WHERE email='$email'";
$query = mysqli_query($con, $sql);

if(mysqli_num_rows($query)>0){
    while($row = mysqli_fetch_assoc($query)) {
        $genNumServer = $row["genNum"];
    }
}
if($genNumClient == $genNumServer){
    $Data->status = 200;
    $Data->message = "Successful";
}else{
    $Data->status = 501;
    $Data->message = "Wrong number!,please check E-mail again";
}

// Retieve value json format to client ------------------------------------------------------------------------------------------------------
$retrieve_json = json_encode($Data);
echo $retrieve_json;

// Close table DB & session -----------------------------------------------------------------------------------------------------------------
mysqli_close($con);
session_write_close();

?>