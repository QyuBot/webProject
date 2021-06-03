<?php

require_once $_SERVER["DOCUMENT_ROOT"]."/db/db_function.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/db/user/userService.php";

$resultEcho = "exception";

if (!isset($_POST['loginId']))
    $resultEcho = "no_args";

else {

    $loginId = $_POST['loginId'];

    // ID가 너무 짧으면 예외 발생
    if (isTooShort($loginId))
        $resultEcho = "id_too_short";

    else {
        $isExist = isLoginIdExist(isTooShort($loginId));

        if ($isExist)
            $resultEcho = "exist";
        else
            $resultEcho = "not_exist";
    }
}

echo $resultEcho;

