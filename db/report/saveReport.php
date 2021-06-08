<?php
// 리포트 등록 php
require_once $_SERVER["DOCUMENT_ROOT"] . "/db/project/projectService.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/db/report/reportService.php";

// 비어있는 매개변수 없는지 확인
if (!isset($_POST['title'])
    || !isset($_POST['contents'])
    || !isset($_POST['projectId'])) {
    echo "missing_arg(s)";
    exit;
}

// 유저가 프로젝트에 참가중이지 않은 경우
if (session_status() == PHP_SESSION_NONE)
    session_start();

$creatorId = $_SESSION['sess'];
if (!isUserJoinedProject($_POST['projectId'], $creatorId)) {
    echo "access_denied";
    exit;
}

$result = addReport($creatorId, $_POST['title'], $_POST['contents'], $_POST['projectId']);
if ($result)
    echo "success";
else
    echo "fail";
