<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/db/project/projectService.php";

// 비어있는 매개변수 없는지 확인
if (!isset($_POST['projectId']) || !isset($_POST['userId'])) {
    echo "missing_arg(s)";
    exit;
}

if (session_status() == PHP_SESSION_NONE)
    session_start();

$project = getProjectByProjectId($_POST['projectId']);
$quitUserId = $_POST['userId'];
$requestUserId = $_SESSION['sess'];

// 없는 프로젝트인 경우
if (empty($project)) {
    echo "access_denied";
    exit;
}

// 나가는 유저가 프로젝트에 참가 중이 아닌 경우
if (!isUserJoinedProject($_POST['projectId'], $quitUserId)) {
    echo "access_denied";
    exit;
}

// 요청자와 나가는 유저가 다른 경우
if ($quitUserId != $requestUserId) {

    // 요청자가 관리자도 아닌 경우
    if ($requestUserId != $project['project_admin_id']) {
        echo "access_denied";
        exit;
    }
}

// 나가는 유저가 프로젝트 관리자인 경우
if ($project['project_admin_id'] == $quitUserId) {
    echo "is_admin";
    exit;
}

$result = quitUserFromProject($_POST['projectId'], $quitUserId);
if ($result)
    echo "success";
else
    echo "fail";