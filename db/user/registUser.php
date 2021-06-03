<?php
require_once $_SERVER["DOCUMENT_ROOT"]."/db/db_function.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/db/user/userService.php";

$resultEcho = "exception";

if (!isset($_POST['login_id'])
    || !isset($_POST['password'])
    || !isset($_POST['nickname'])
    || !isset($_POST['email'])
    || !isset($_POST['email_domain'])) {
    echo "missing_arg(s)";
    exit;
}


$addUserResult = addUser(
    $_POST['login_id'],
    $_POST['password'],
    $_POST['nickname'],
    $_POST['email'],
    $_POST['email_domain']);

if ($addUserResult)
    $resultEcho = "success";
else
    $resultEcho = "fail";

echo $resultEcho;