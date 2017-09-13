<?php
/**
 * Created by PhpStorm.
 * User: Neramit777
 * Date: 9/11/2017
 * Time: 11:48 AM
 */
// Set time zone to Bangkok in Asia ----------------------------------------------------------------------------------------------------------------------------------
date_default_timezone_set('Asia/Bangkok');

// Tell header for content-type is json format -----------------------------------------------------------------------------------------------------------------------
header('Content-Type: application/json');

// Check token -------------------------------------------------------------------------------------------------------------------------------------------------------
require_once('CheckToken.php');
if ($checkToken == 1) {
    $username = $name;
    require_once('DBconnect.php');
    // Get display name
    $sql = "SELECT displayName FROM users WHERE username='$Username' COLLATE Latin1_General_CS_AS";
    $query = mysqli_query($con, $sql);
    if (mysqli_num_rows($query) == 1) {
        while ($row = mysqli_fetch_assoc($query)) {
            if ($row["displayName"] == null) {
                $data->displayName = $searchUsername;
            } else
                $data->displayName = $row["displayName"];
            $Data->data = $data;
        }
        // Not have username -------------------------------------------------------------------------------------------------------------------------------------
    }
    else{

    }
}

?>