<style>
    .issue {
        border: 1px solid black;
    }
    main {
        line-height: 35px;
    }
    button {
        font-size: 16px;
        width: 80px;
        text-align: center;
        height: 46px;
        border-radius:10px;
        border-color: #917b56;
        color: #fff;
    }
</style>
<?php
if(!defined('DirectAccessCheck')){
    header('HTTP/1.0 404 Not Found', true, 404);
    exit;
}

?>
<main>
    <h3>ISSUE<button type="button" style = "float:right; margin-left: 20px;" onclick="location.href='/?projectId=<?=$_GET['projectId']?>&page=issueEditor';">추가</button></h3>
    <hr>
    <?php

    require_once $_SERVER["DOCUMENT_ROOT"] . "/db/issue/issueService.php";
    $issues = getIssueListinProject($_GET['projectId']);
    if (count($issues) == 0)
        echo "이정표(마일스톤)가 없네요. 길을 잃었어요";
    else {
        foreach ($issues as $issue) {
            echo "<div class='issue' style='border: none'>";
            echo "이슈 제목 : {$issue['issue_title']}<br>";
            echo "상태 : ".($issue['issue_status'] == 1 ? "해결됨" : "해결안됨")."<br>";
            echo "우선순위 : {$issue['issue_priority']} 순위<br>"; 
            echo "<button style ='width:100px; margin-top:20px;' onclick= location.href='/?projectId={$_GET['projectId']}&page=issueViewer&issueId={$issue['issue_id']}';>이슈조회</button><br>";
            echo "</div><br>";
        }
    }

    ?>

    <br><br>
    <h3>이슈 목록</h3>
</main>
<hr>
