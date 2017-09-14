<?php
/**
 * Created by PhpStorm.
 * User: Neramit777
 * Date: 9/5/2017
 * Time: 2:08 PM
 */

$json = $_POST['json'];
$salt = "a059a744729dfc7a4b4845109f591029";

echo $token = md5($salt.md5($json.$salt));
?>