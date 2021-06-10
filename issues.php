<?php
if(!defined('DirectAccessCheck')){
    header('HTTP/1.0 404 Not Found', true, 404);
    exit;
}

?>

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
<main>
    <h2>ISSUE<button type="button" style = "float:right; margin-left: 20px;" onclick="location.href='/?projectId=<?=$_GET['projectId']?>&page=issueEditor';">추가</button></h2>
    <br>
    <?php
    require_once $_SERVER["DOCUMENT_ROOT"] . "/db/issue/issueService.php";

    $solvedIssues = getIssueListinProjectAndSolved($project['project_id']);
    $unsolvedIssues = getIssueListinProjectAndNotSolved($project['project_id']);
    $totalNumOfIssues = count($solvedIssues) + count($unsolvedIssues);

    // 이슈가 하나도 없을 경우
    if ($totalNumOfIssues == 0)
        echo "이슈가 하나도 없어요. 천리길도 한걸음부터";
    else {

        echo "<h3>미해결 이슈</h3><hr>";
        if (count($unsolvedIssues) == 0)
            echo "이슈를 전부 해결했어요. <a href='location.href='/?projectId={$_GET['projectId']}&page=issueEditor'>새로운 이슈를 만들어볼까요?</a>";
        else {
            foreach ($unsolvedIssues as $issue) {
                echo "<div class='issue' style='border: none'>";
                echo "이슈 제목 : {$issue['issue_title']}<br>";
                echo "상태 : ".($issue['issue_status'] == 1 ? "해결됨" : "해결안됨")."<br>";
                echo "우선순위 : {$issue['issue_priority']} 순위<br>";
                echo "작성일 : {$issue['issue_create_time']}<br>";
                echo "<button style ='width:100px; margin-top:20px;' onclick= location.href='/?projectId={$_GET['projectId']}&page=issueViewer&issueId={$issue['issue_id']}';>이슈조회</button><br>";
                echo "</div><br>";
            }
        }

        echo "<h3>해결된 이슈</h3><hr>";
        if (count($solvedIssues) == 0)
            echo "해결된 이슈가 없어요. 열심히 일을 해봐요";
        else {
            foreach ($solvedIssues as $issue) {
                echo "<div class='issue' style='border: none'>";
                echo "이슈 제목 : {$issue['issue_title']}<br>";
                echo "상태 : ".($issue['issue_status'] == 1 ? "해결됨" : "해결안됨")."<br>";
                echo "우선순위 : {$issue['issue_priority']} 순위<br>";
                echo "작성일 : {$issue['issue_create_time']}<br>";
                echo "<button style ='width:100px; margin-top:20px;' onclick= location.href='/?projectId={$_GET['projectId']}&page=issueViewer&issueId={$issue['issue_id']}';>이슈조회</button><br>";
                echo "</div><br>";
            }
        }

    }

    ?>
</main>
<hr>
