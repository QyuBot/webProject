<?php
require_once $_SERVER["DOCUMENT_ROOT"]."/db/user/userService.php";

$resultEcho = "exception";

if (!isset($_POST['nickname']))
    $resultEcho = "no_args";

else {

    if (isNicknameExist($_POST['nickname']))
        $resultEcho = "exist";
    else
        $resultEcho = "not_exist";
}

echo $resultEcho;

