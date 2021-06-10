<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/db/project/projectService.php";

// 비어있는 매개변수 없는지 확인
if (!isset($_POST['projectId']) || !isset($_POST['prom'])) {
    echo "missing_arg(s)";
    exit;
}

if (session_status() == PHP_SESSION_NONE)
    session_start();

$project = getProjectByProjectId($_POST['projectId']);
$userId = $_SESSION['sess'];

// 없는 프로젝트인 경우
if (empty($project)) {
    echo "access_denied";
    exit;
}

// 요청자가 프로젝트에 참가 중이 아닌 경우
if (!isUserJoinedProject($_POST['projectId'], $userId)) {
    echo "access_denied";
    exit;
}

// 프로젝트 관리자가 아닌 경우
if (!$project['project_admin_id'] == $userId) {
    echo "access_denied";
    exit;
}

// 프로젝트 이름을 잘못 입력한 경우
if ($project['project_name'] != $_POST['prom']) {
    echo "missing_arg(s)";
    exit;
}

$result = deleteProject($_POST['projectId']);
if ($result == 1)
    echo "success";
else
    echo "fail : {$result}";