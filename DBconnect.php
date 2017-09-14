<?php
/**
 * Created by PhpStorm.
 * User: Neramit777
 * Date: 8/11/2017
 * Time: 11:10 AM
 */

// Define variables -------------------------------------------------------------------------------------------------------------------------
define('HOST','localhost');
define('USER','root');
define('PASS','');
define('DB','android');

// Connect Database ------------------------------------------------------------------------------------------------------------------------
$con = mysqli_connect(HOST,USER,PASS,DB) or die ('Connection failed' . mysqli_error());