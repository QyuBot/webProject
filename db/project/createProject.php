<?php
require_once $_SERVER["DOCUMENT_ROOT"]."/db/user/userService.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/db/project/projectService.php";

$resultEcho = "exception";

if (!isset($_POST['projectName'])) {
    echo "no_args";
    exit;
}

if (session_status() == PHP_SESSION_NONE)
    session_start();

// 존재하지 않는 세션 유저 ID 인 경우
if (!isUserIDExist($_SESSION['sess'])) {
    echo "access_denied";
    exit;
}

// 이미 존재하는 프로젝트 이름인 경우
if (isProjectNameExist($_POST['projectName']))
    $resultEcho = "duplicate_name";

// 문제 없으면 프로젝트 생성 시도
else {
    $result = addProject($_SESSION['sess'], $_POST['projectName']);
    if ($result)
        $resultEcho = "success";
    else
        $resultEcho = "fail";
}

echo $resultEcho;

