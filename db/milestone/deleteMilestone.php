<?php
require_once $_SERVER["DOCUMENT_ROOT"]."/db/project/projectService.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/db/milestone/milestoneService.php";

$resultEcho = "exception";

if (!isset($_POST['milestoneId'])) {
    echo "no_args";
    exit;
}

if (session_status() == PHP_SESSION_NONE)
    session_start();

// 실제로 존재하는 마일스톤인가? (getMilestoneByMilestoneId() 을 존재하지 않는 마일스톤 ID 로 실행하면 빈 배열 반환)
$targetMilestone = getMilestoneByMilestoneId($_POST['milestoneId']);
if (empty($targetMilestone)) {
    echo "not_exist";
    exit;
}

// 유저가 참여한 프로젝트의 마일스톤인가?
if (!isUserJoinedProject($targetMilestone['milestone_inclusion_project_id'], $_SESSION['sess'])) {
    echo "access_denied";
    exit;
}

$result = deleteMilestone($_POST['milestoneId']);

if ($result)
    $resultEcho = "success";
else
    $resultEcho = "fail";

echo $resultEcho;