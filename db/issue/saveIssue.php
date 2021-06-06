<?php
// 이슈 등록 php
require_once $_SERVER["DOCUMENT_ROOT"] . "/db/project/projectService.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/db/milestone/milestoneService.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/db/issue/issueService.php";

if (session_status() == PHP_SESSION_NONE)
    session_start();


// 비어있는 매개변수 없는지 확인
if (!isset($_POST['title'])
    || !isset($_POST['contents'])
    || !isset($_POST['priority'])
    || !isset($_POST['status'])
    || !isset($_POST['projectId'])
    || !isset($_POST['milestoneId'])) {
    echo "missing_arg(s)";
    exit;
}

// 유저가 프로젝트에 참가중이지 않은 경우
$creatorId = $_SESSION['sess'];
if (!isUserJoinedProject($_POST['projectId'], $creatorId)) {
    echo "access_denied";
    exit;
}

// 프로젝트에 존재하지 않는 마일스톤 ID 이 아닌 경우
$projectId = $_POST['projectId'];
$milestoneId = $_POST['milestoneId'];
if ($milestoneId != -1) {
    $milestone = getMilestoneByMilestoneId($milestoneId);
    if ($milestone['milestone_inclusion_project_id'] != $projectId) {
        echo "mid : ".$milestone['milestone_inclusion_project_id'];
        echo "pid : ".$projectId;
        echo "milestone_error";
        exit;
    }
}

// 우선순위, 상태가 범위 외 일 경우
$priority = $_POST['priority'];
$status = $_POST['status'];
if (!(1 <= $priority && $priority <= 4)) {
    echo "priority_error";
    exit;
}

if (!(0 <= $status && $status <= 1)) {
    echo "status_error";
    exit;
}

$result = addIssue($creatorId, $_POST['title'], $_POST['contents'], $priority, $status, $projectId, $milestoneId);
if ($result)
    echo "success";
else
    echo "fail";
