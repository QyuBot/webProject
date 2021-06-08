<?php
// 이슈 등록 php
require_once $_SERVER["DOCUMENT_ROOT"] . "/db/project/projectService.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/db/issue/issueService.php";

// 비어있는 매개변수 없는지 확인
if (!isset($_POST['issueId'])) {
    echo "missing_arg(s)";
    exit;
}


if (session_status() == PHP_SESSION_NONE)
    session_start();

$issue = getIssueByIssueId($_POST['issueId']);
$project = getProjectByProjectId($issue['issue_inclusion_project_id']);
$creatorId = $_SESSION['sess'];

// 요청자가 프로젝트에 참가중이지 않은 경우
if (!isUserJoinedProject($issue['issue_inclusion_project_id'], $creatorId)) {
    echo "access_denied";
    exit;
}

// 요청자가 이슈 작성자거나, 이슈가 들어있는 프로젝트의 관리자일 경우에만 삭제 진행
if ($creatorId == $project['project_admin_id'] || $creatorId == $issue['issue_creator_id']) {
    $result = deleteIssue($_POST['issueId']);
    if ($result)
        echo "success";
    else
        echo "fail";
}
else {
    echo "access_denied";
}