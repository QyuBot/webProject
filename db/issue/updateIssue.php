<?php
// 이슈 등록 php
require_once $_SERVER["DOCUMENT_ROOT"] . "/db/project/projectService.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/db/milestone/milestoneService.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/db/issue/issueService.php";

// 비어있는 매개변수 없는지 확인
if (!isset($_POST['title'])
    || !isset($_POST['contents'])
    || !isset($_POST['priority'])
    || !isset($_POST['status'])
    || !isset($_POST['milestoneId'])
    || !isset($_POST['issueId'])) {
    echo json_encode($_POST);
    exit;
}

// 수정하려는 이슈가 존재하지 않는 경우
$issue = getIssueByIssueId($_POST['issueId']);
if (empty($issue)) {
    echo "issue_not_exist";
    exit;
}


// 유저가 프로젝트에 참가중이지 않은 경우
if (session_status() == PHP_SESSION_NONE)
    session_start();

$projectId = $issue['issue_inclusion_project_id'];
$creatorId = $_SESSION['sess'];
if (!isUserJoinedProject($projectId, $creatorId)) {
    echo "access_denied";
    exit;
}

// 프로젝트에 존재하지 않는 마일스톤 ID 이 아닌 경우
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

$result = editIssue($_POST['issueId'], $_POST['title'], $_POST['contents'], $priority, $status, $milestoneId);
if ($result)
    echo "success";
else
    echo "fail";
