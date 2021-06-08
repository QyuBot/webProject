<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/db/user/userService.php";


if (!isset($_POST['input_login_login_id']) || !isset($_POST['input_login_password'])) {
    echo "<script type='text/javascript'>alert('ID, PW를 입력해주세요.');</script>";
}
else {
    $result = doLogin($_POST['input_login_login_id'], $_POST['input_login_password']);

    if (!$result) {
        echo "<script type='text/javascript'>alert('로그인 실패');</script>";
    }
    else {
        session_start();
        $_SESSION["sess"] = $result;
        echo "<script type='text/javascript'>alert('로그인 성공');</script>";
    }
}

echo "<script type='text/javascript'>window.location.href='/';</script>";