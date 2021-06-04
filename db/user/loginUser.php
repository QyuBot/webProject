<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/db/user/userService.php";


if (!isset($POST_['input_login_id']) || !isset($POST_['input_password'])) {
    echo "missing_arg(s)";
}

$result = doLogin($_POST['input_login_id'], $_POST['input_password']);

if (!$result) {
    echo "<script type='text/javascript'>alert('로그인 실패');</script>";
}
else {
    session_start();
    $_SESSION["sess"] = $result;
    echo "<script type='text/javascript'>alert('로그인 성공');</script>";
}

echo "<script type='text/javascript'>window.location.href='/exLogin.php';</script>";