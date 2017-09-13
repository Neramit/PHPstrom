<?php
/**
 * Created by PhpStorm.
 * User: Neramit777
 * Date: 9/4/2017
 * Time: 2:45 PM
 */

// Set time zone to Bangkok in Asia ---------------------------------------------------------------------------------------------------------
date_default_timezone_set('Asia/Bangkok');

// Tell header for content-type is json format ----------------------------------------------------------------------------------------------
header('Content-Type: application/json');

// Declare variables ------------------------------------------------------------------------------------------------------------------------
$jsonR = $_SESSION['token'];



// Close table DB ---------------------------------------------------------------------------------------------------------------------------
mysqli_close($con);

?>