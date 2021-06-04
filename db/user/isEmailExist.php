<?php
require_once $_SERVER["DOCUMENT_ROOT"]."/db/user/userService.php";

$resultEcho = "exception";

if (!isset($_POST['email']))
    $resultEcho = "no_email";
else if (!isset($_POST['email_domain']))
    $resultEcho = "no_domain";
else {

    if ($_POST['email'] == "" || $_POST['email_domain'] == "")
        $resultEcho = "empty_field";
    else {
        $isExist = isEmailExist($_POST['email'], $_POST['email_domain']);

        if ($isExist)
            $resultEcho = "exist";
        else
            $resultEcho = "not_exist";
    }
}

echo $resultEcho;

