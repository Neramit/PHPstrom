<?php //include Class -----------------------------------------------------------------------------------------------------------------------
include("DataReceive.php");
?>

<?php
/**
 * Created by PhpStorm.
 * User: Neramit777
 * Date: 8/24/2017
 * Time: 4:05 PM
 */

// Set time zone to Bangkok in Asia
date_default_timezone_set('Asia/Bangkok');

//Start session
session_start();

// Tell header for content-type is json format ----------------------------------------------------------------------------------------------
header('Content-Type: application/json');

$json = $_POST['json'];
$jsonR = json_decode($json, true);

// Declare variables for receive module ,target ---------------------------------------------------------------------------------------------
$dataReceive = new DataReceive();
$dataReceive->setModule($jsonR['module']);
$dataReceive->setTarget($jsonR['target']);
$dataReceive->setData($jsonR['data']);

// Controller to each API (Web server) ------------------------------------------------------------------------------------------------------
// TODO:Module Authentication ---------------------------------------------------------------------------------------------------------------
if ($dataReceive->getModule() == "Authentication") {
    if ($dataReceive->getTarget() == "register") {
        $_SESSION['data'] = $dataReceive->getData();
        require_once("Register.php");
    } else if ($dataReceive->getTarget() == "login") {
        $_SESSION['data'] = $dataReceive->getData();
        require_once("Login.php");
    } else if ($dataReceive->getTarget() == "firebase") {
        $dataReceive->setToken($jsonR['token']);
        $_SESSION['token'] = $dataReceive->getToken();
        $_SESSION['data'] = $dataReceive->getData();
        require_once("SendRegistrationID.php");
    }
} // TODO:Module Forgot password --------------------------------------------------------------------------------------------------------------
else if ($dataReceive->getModule() == "Forgot password") {
    if ($dataReceive->getTarget() == "enterEmail") {
        $_SESSION['data'] = $dataReceive->getData();
        require_once("EnterEmail.php");
    } else if ($dataReceive->getTarget() == "checkEmail") {
        $_SESSION['data'] = $dataReceive->getData();
        require_once("CheckEmail.php");
    } else if ($dataReceive->getTarget() == "resetPassword") {
        $_SESSION['data'] = $dataReceive->getData();
        require_once("ResetPassword.php");
    }
} // TODO:Module Friend -----------------------------------------------------------------------------------------------------------------------
else if ($dataReceive->getModule() == "Friend") {
    if ($dataReceive->getTarget() == "friendTabEnter") {
        $dataReceive->setToken($jsonR['token']);
        $_SESSION['token'] = $dataReceive->getToken();
        require_once("EnterMain.php");
    } else if ($dataReceive->getTarget() == "addFriendSearchButton") {
        $dataReceive->setToken($jsonR['token']);
        $_SESSION['token'] = $dataReceive->getToken();
        $_SESSION['data'] = $dataReceive->getData();
        require_once("SearchUser.php");
    } else if ($dataReceive->getTarget() == "addFriendAddButton") {
        $dataReceive->setToken($jsonR['token']);
        $_SESSION['token'] = $dataReceive->getToken();
        $_SESSION['data'] = $dataReceive->getData();
        require_once("AddFriend.php");
    }else if ($dataReceive->getTarget() == "acceptFriendRequest") {
        $dataReceive->setToken($jsonR['token']);
        $_SESSION['token'] = $dataReceive->getToken();
        $_SESSION['data'] = $dataReceive->getData();
        require_once("AcceptFriend.php");
    }else if ($dataReceive->getTarget() == "declineFriendRequest") {
        $dataReceive->setToken($jsonR['token']);
        $_SESSION['token'] = $dataReceive->getToken();
        $_SESSION['data'] = $dataReceive->getData();
        require_once("DeclineFriend.php");
    }
} // TODO:Module Other -----------------------------------------------------------------------------------------------------------------------
else if ($dataReceive->getModule() == "Other") {
    if ($dataReceive->getTarget() == "profileAccountDisplayName") {
        $dataReceive->setToken($jsonR['token']);
        $_SESSION['token'] = $dataReceive->getToken();
        $_SESSION['data'] = $dataReceive->getData();
        require_once("ChangeDisplayname.php");
    } elseif ($dataReceive->getTarget() == "profileAccountDisplayPicture") {
        $dataReceive->setToken($jsonR['token']);
        $_SESSION['token'] = $dataReceive->getToken();
        $_SESSION['data'] = $dataReceive->getData();
        require_once("ChangeDisplayPicture.php");
    } elseif ($dataReceive->getTarget() == "deleteAccount") {
        $dataReceive->setToken($jsonR['token']);
        $_SESSION['token'] = $dataReceive->getToken();
        require_once("DeleteAccount.php");
    }
} // TODO:Module Group -----------------------------------------------------------------------------------------------------------------------
else if ($dataReceive->getModule() == "Group") {
    if ($dataReceive->getTarget() == "createGroup") {
        $dataReceive->setToken($jsonR['token']);
        $_SESSION['token'] = $dataReceive->getToken();
        $_SESSION['data'] = $dataReceive->getData();
        require_once("CreateGroup.php");
    } elseif ($dataReceive->getTarget() == "getGroupUID") {
        $dataReceive->setToken($jsonR['token']);
        $_SESSION['token'] = $dataReceive->getToken();
        $_SESSION['data'] = $dataReceive->getData();
        require_once("GetGroupUID.php");
    } else if ($dataReceive->getTarget() == "groupTabEnter") {
        $dataReceive->setToken($jsonR['token']);
        $_SESSION['token'] = $dataReceive->getToken();
        require_once("EnterGroup.php");
    } else if ($dataReceive->getTarget() == "joinGroupRequest") {
        $dataReceive->setToken($jsonR['token']);
        $_SESSION['token'] = $dataReceive->getToken();
        $_SESSION['data'] = $dataReceive->getData();
        require_once("JoinGroup.php");
    } else if ($dataReceive->getTarget() == "ignoreGroupRequest") {
        $dataReceive->setToken($jsonR['token']);
        $_SESSION['token'] = $dataReceive->getToken();
        $_SESSION['data'] = $dataReceive->getData();
        require_once("IgnoreGroup.php");
    }
} // TODO:Module Group Detail ----------------------------------------------------------------------------------------------------------------
else if ($dataReceive->getModule() == "GroupDetail") {
    if ($dataReceive->getTarget() == "enterGroupDetail") {
        $dataReceive->setToken($jsonR['token']);
        $_SESSION['token'] = $dataReceive->getToken();
        $_SESSION['data'] = $dataReceive->getData();
        require_once("enterGroupDetail.php");
    }
}
?>
