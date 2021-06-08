<?php
// 이슈 등록 php
require_once $_SERVER["DOCUMENT_ROOT"] . "/db/project/projectService.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/db/report/reportService.php";

// 비어있는 매개변수 없는지 확인
if (!isset($_POST['title'])
    || !isset($_POST['contents'])
    || !isset($_POST['reportId'])) {
    echo json_encode($_POST);
    exit;
}

// 수정하려는 리포트가 존재하지 않는 경우
$report = getReportByReportId($_POST['reportId']);
if (empty($report)) {
    echo "report_not_exist";
    exit;
}


// 유저가 프로젝트에 참가중이지 않은 경우
if (session_status() == PHP_SESSION_NONE)
    session_start();

$projectId = $report['report_inclusion_project_id'];
$creatorId = $_SESSION['sess'];
if (!isUserJoinedProject($projectId, $creatorId)) {
    echo "access_denied";
    exit;
}

$result = editReport($_POST['reportId'], $_POST['title'], $_POST['contents']);

echo $result;