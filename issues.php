<style>
    .issue {
        border: 1px solid black;
    }
</style>
<?php
if(!defined('DirectAccessCheck')){
    header('HTTP/1.0 404 Not Found', true, 404);
    exit;
}

?>
<br>
<a href="/?projectId=<?=$_GET['projectId']?>&page=issueEditor">이슈 새로 작성하러가기</a>
<br>
<main>
    <h3>ISSUE</h3>
    <hr>
    <?php

    require_once $_SERVER["DOCUMENT_ROOT"] . "/db/issue/issueService.php";
    $issues = getIssueListinProject($_GET['projectId']);
    if (count($issues) == 0)
        echo "이정표(마일스톤)가 없네요. 길을 잃었어요";
    else {
        foreach ($issues as $issue) {
            echo "<div class='issue'>";
            echo "이슈 제목 : {$issue['issue_title']}<br>";
            echo "상태 : ".($issue['issue_status'] == 1 ? "해결됨" : "해결안됨")."<br>";
            echo "우선순위 : {$issue['issue_priority']} 순위<br>";
            echo "<a href='/?projectId={$_GET['projectId']}&page=issueViewer&issueId={$issue['issue_id']}'>이슈 조회하기</a><br>";
            echo "</div>";
        }
    }

    ?>
    <br>
</main>
<h1>이슈 목록</h1>
<hr>
