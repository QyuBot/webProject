<?php
// 이슈 등록 php
require_once $_SERVER["DOCUMENT_ROOT"] . "/db/project/projectService.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/db/comment/commentService.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/db/issue/issueService.php";

// 비어있는 매개변수 없는지 확인
if (!isset($_POST['issueId'])
    || !isset($_POST['content'])) {
    echo "missing_arg(s)";
    exit;
}

// 없는 이슈인 경우
$issue = getIssueByIssueId($_POST['issueId']);
if (empty($issue)) {
    echo "issue_not_exist";
    exit;
}

// 내용이 비어있는 경우
if ($_POST['content'] == "") {
    echo "content_empty";
    exit;
}

// 유저가 작성하려는 이슈가 포함된 프로젝트에 참가중이지 않은 경우
if (session_status() == PHP_SESSION_NONE)
    session_start();

$creatorId = $_SESSION['sess'];
if (!isUserJoinedProject($issue['issue_inclusion_project_id'], $creatorId)) {
    echo "access_denied";
    exit;
}

$result = addComment($issue['issue_id'], $creatorId, $_POST['content']);
if ($result)
    echo "success";
else
    echo "fail";
