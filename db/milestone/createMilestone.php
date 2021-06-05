<?php
require_once $_SERVER["DOCUMENT_ROOT"]."/db/project/projectService.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/db/milestone/milestoneService.php";

$resultEcho = "exception";

if (!isset($_POST['milestoneName']) || !isset($_POST['projectId'])) {
    echo "no_args";
    exit;
}

if (session_status() == PHP_SESSION_NONE)
    session_start();

// 유저가 프로젝트에 참가중이지 않은 경우
if (isUserJoinedProject($_POST['projectId'], $_SESSION['sess'])) {
    echo "access_denied";
    exit;
}

// 이미 존재하는 마일스톤 이름인 경우
if (isMilestoneNameExist($_POST['projectId'], $_POST['milestoneName']))
    $resultEcho = "duplicate_name";

// 문제 없으면 프로젝트 생성 시도
else {
    $result = addMilestone($_POST['projectId'], $_POST['milestoneName']);
    if ($result)
        $resultEcho = "success";
    else
        $resultEcho = "fail";
}

echo $resultEcho;

