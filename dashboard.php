
<?php
if(!defined('DirectAccessCheck')){
    header('HTTP/1.0 404 Not Found', true, 404);
    exit;
}
?>
<style type="text/css">
    .setting-projectbox{
        margin-top: 20px;
        border: 1px solid;
        float: left;
        width: 80%;
        padding: 20px;
    }

    .setting-memberbox{
        border: 1px solid;
        float: left;
        width: 80%;
        padding: 20px;
        margin-top: 30px;
    }
    .setting-membernamebox{
        margin-left: 15px;
    }
</style>
<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/db/user/userService.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/db/project/projectService.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/db/issue/issueService.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/db/report/reportService.php";


$collaborators = getAllProjectCollaborators($project['project_id']);
$numOfSolvedIssues = count(getIssueListinProjectAndSolved($project['project_id']));
$numOfUnsolvedIssues = count(getIssueListinProjectAndNotSolved($project['project_id']));
$numOfReports = count(getReportListinProject($project['project_id']));
$totalNumOfIssues = $numOfSolvedIssues + $numOfUnsolvedIssues;

?>
<main>
    <h3>대쉬보드</h3>
    <hr>
    <div class="setting-projectbox">
        <h3>프로젝트 정보</h3>
        <div style="padding-top: 10px;">프로젝트 이름 : <?php echo($project['project_name']); /* $project 는 index.php 에서 선언되어 내려옴 */  ?></div>
        <div>구성원 수 : <?php echo count($collaborators) ?></div>
        <div>이슈 갯수 : <?=$totalNumOfIssues?> (<?php if($totalNumOfIssues != 0) echo "미해결:{$numOfUnsolvedIssues} / 해결:{$numOfSolvedIssues}"; ?>)</div>
        <div>보고서 수 : <?=$numOfReports?></div>


    </div>
    <?php

    // 현재 접속한 유저가 프로젝트 관리자 일 경우 -> 삭제하기 버튼 출력
    if ($project['project_admin_id'] == $nowLoginUser['user_id']) {
        echo "<div>";
        echo "<h1>Danger Zone</h1>";
        echo "<hr>";
        echo "<a href='' onclick='deleteProject();'>프로젝트 삭제하기</a>";
        echo "</div>";
    }

    ?>
</main>