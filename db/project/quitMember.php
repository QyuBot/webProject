<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/db/project/projectService.php";

// 비어있는 매개변수 없는지 확인
if (!isset($_POST['projectId'])) {
    echo "missing_arg(s)";
    exit;
}

// 요청자가 프로젝트에 참가 중이 아닌 경우
if (session_status() == PHP_SESSION_NONE)
    session_start();

$project = getProjectByProjectId($_POST['projectId']);
$userId = $_SESSION['sess'];

if (empty($project)) {
    echo "prject_not_exist";
    exit;
}

if (!isUserJoinedProject($_POST['projectId'], $userId)) {
    echo "not_join";
    exit;
}

$result = quitUserFromProject($_POST['projectId'], $userId);
if ($result)
    echo "success";
else
    echo "fail";