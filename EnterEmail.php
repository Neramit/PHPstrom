<?php
/**
 * Created by PhpStorm.
 * User: Neramit777
 * Date: 8/29/2017
 * Time: 4:20 PM
 */

// Set time zone to Bangkok in Asia ---------------------------------------------------------------------------------------------------------
date_default_timezone_set('Asia/Bangkok');

// Tell header for content-type is json format ----------------------------------------------------------------------------------------------
header('Content-Type: application/json');

//require("PHPMailer/class.phpmailer.php"); // path to the PHPMailer class.
require("PHPMailer/PHPMailerAutoload.php"); // path to the PHPMailer class.

// Declare variables ------------------------------------------------------------------------------------------------------------------------
$jsonR = $_SESSION['data'];
$email = $jsonR['email'];
//$email = $jsonR;
$Data = new \stdClass();

$genNum = rand(111111, 999999);
//$date = new DateTime();
//$date = $date->getTimestamp();
//$date = substr($date, 5, 6);
//PHPMailer Object
$mail = new PHPMailer;

//From email address and name
//Enable SMTP debugging
$mail->isSMTP();
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
$mail->SMTPDebug = 0;
//Ask for HTML-friendly debug output
$mail->Debugoutput = 'html';
//Set the hostname of the mail server
$mail->Host = "smtp.gmail.com";
//Set the SMTP port number - likely to be 25, 465 or 587
$mail->Port = 587;
//Set the encryption system to use - ssl (deprecated) or tls
$mail->SMTPSecure = 'tls';
//Whether to use SMTP authentication
$mail->SMTPAuth = true;
//Username to use for SMTP authentication
$mail->Username = "chatchat.developer@gmail.com";
//Password to use for SMTP authentication
$mail->Password = "ARsoft56113039-4";
//Set who the message is to be sent from
$mail->setFrom('chatchat.developer@gmail.com', 'Chat Chat Application');
//Set who the message is to be sent to
$mail->addAddress($email, 'Chat Chat customer');
//Set the subject line
$mail->Subject = 'Your generate number';
//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
//$mail->msgHTML(file_get_contents('content.html'), dirname(__FILE__));
$mail->msgHTML(" Generate number is : " . $genNum);

// Connect Database -------------------------------------------------------------------------------------------------------------------------
require_once('DBconnect.php');
$sql = "SELECT * FROM users WHERE email='$email'";
$check = mysqli_fetch_array(mysqli_query($con, $sql));

// Check already exist ----------------------------------------------------------------------------------------------------------------------
if (isset($check)) {
    if ($mail->send()) {
        $sql = "SELECT * FROM gennum_expire_and_email WHERE email='$email'";
        $check = mysqli_fetch_array(mysqli_query($con, $sql));
        if (isset($check)) {
            $date = new DateTime();
            $TimeToExpired = $date->getTimestamp() + 180;
            $sql = "UPDATE gennum_expire_and_email SET genNum = '$genNum',timeToExpired = '$TimeToExpired' WHERE email='$email'";
            if ($con->query($sql) == TRUE) {
                $Data->status = 200;
                $Data->message = "Sent E-mail";
            } else {
                $Data->status = 503;
                $Data->message = "Error updating genNum";
            }
        }else{
            $date = new DateTime();
            $TimeToExpired = $date->getTimestamp() + 180;
            $sql = "INSERT INTO gennum_expire_and_email (genNum,timeToExpired,email) VALUES('$genNum','$TimeToExpired','$email')";
            if(mysqli_query($con,$sql)){
                $Data -> status = 200;
                $Data -> message = "Sent E-mail";
                //$Data -> message = "An account have been create";
            }else{
                $Data -> status = 401;
                $Data -> message = "Error insert genNum";
            }
        }
    } else {
        $Data->status = 501;
        $Data->message = "Can't send E-mail : " . $mail->ErrorInfo;
//    echo "Message has been sent successfully";
    }
} else {
    $Data->status = 502;
    $Data->message = "This isn't registered E-mail";
}
// Retieve value json format to client ------------------------------------------------------------------------------------------------------
$retrieve_json = json_encode($Data);
echo $retrieve_json;

// Close table DB & session -----------------------------------------------------------------------------------------------------------------
mysqli_close($con);
session_write_close();

?>
