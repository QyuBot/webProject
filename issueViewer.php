
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<style>

    .article{
        margin: 2%;
        border: 1px solid black;
    }
    .comment  {
        border: 1px solid gray;
        margin: 3px;
        padding: 3px;
        width: 600px;
        height: 140px;
    }


</style>

<br>
<h3>이슈 조회</h3>
<hr>
<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/db/user/userService.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/db/milestone/milestoneService.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/db/issue/issueService.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/db/project/projectService.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/db/comment/commentService.php";

if(!defined('DirectAccessCheck')){
    header('HTTP/1.0 404 Not Found', true, 404);
    exit;
}

// 이슈 ID 매개변수가 없는가?
if (!isset($_GET['issueId'])) {
    header("Location: /?projectId={$_GET['projectId']}&page=issue");
    exit;
}

// 이슈 ID 가 없는 이슈인가?
$issue = getIssueByIssueId($_GET['issueId']);
if (empty($issue)) {
    header("Location: /?projectId={$_GET['projectId']}&page=issue");
    exit;
}

// URL 의 프로젝트 ID 와 이슈의 지정된 프로젝트 ID가 다른가?
if ($_GET['projectId'] != $issue['issue_inclusion_project_id']) {
    header("Location: /?projectId={$_GET['projectId']}&page=issue");
    exit;
}

// 접속중인 유저가 해당 이슈를 볼 권한이 있는가? (프로젝트에 소속되어 있는가?)
if (session_status() == PHP_SESSION_NONE)
    session_start();
$isJoin = isUserJoinedProject($issue['issue_inclusion_project_id'], $_SESSION['sess']);

if (!$isJoin) {
    header("Location: /?projectId={$_GET['projectId']}&page=issue");
    exit;
}

// 작성자 구하기
$creator = getUserByUserId($issue['issue_creator_id']);
$creatorNickname = "알 수 없음";
if (!empty($creator))
    $creator_nickname = $creator['user_nickname'];

// 포함된 마일스톤 구하기
$milestone = array();
if (!$issue['issue_inclusion_milestone_id'] == null) {
    $milestone = getMilestoneByMilestoneId($issue['issue_inclusion_milestone_id']);
}

// 댓글 목록 구하기
$comments = getCommentcontainsIssue($issue['issue_id']);
?>

<h3>제목 : <?=$issue['issue_title']?></h3>
<br>
<p>배정된 마일스톤 : <?php echo (empty($milestone) ? "마일스톤 없음" : $milestone['milestone_name']);?></p>
<p>이슈 작성자 닉네임 : <?=$creatorNickname?></p>
<p>이슈 우선순위 : <?=$issue['issue_priority']?> 순위</p>
<p>이슈 상태 : <?php echo ($issue['issue_status'] == 1 ? "해결됨" : "해결안됨");?></p>
<h3>이슈 본문</h3>
<hr>
<div class="article">
    <?php echo $issue['issue_article']?>
</div>
<hr>
<h3>댓글 영역</h3>
<input type="hidden" id="hidden_issueId" value="<?=$issue['issue_id']?>">
댓글 작성하기 <input type="text" id="writecomment"><button type="button" class="btn btn-primary" onclick="postComment();">작성하기</button>
<br>
<br>
<?php
    if (count($comments) == 0)
        echo "<h3>아직 댓글이 없어요</h3>";
    else {

        foreach ($comments as $comment) {
            $commentCreator = getUserByUserId($comment['comment_creator_id']);
            $commentCreatorNickname = "알 수 없음";
            if (!empty($commentCreator))
                $commentCreatorNickname = $commentCreator['user_nickname'];
            echo "<div class='comment'>";
            echo "<p>작성자 : {$commentCreatorNickname} / 작성일자 : {$comment['comment_create_time']}</p><br>";
            echo "<p>내용 : {$comment['comment_content']}</p>";
            echo "</div>";

        }
    }
?>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<script type="text/javascript">

    function postComment() {
        const issue_id = document.getElementById('hidden_issueId').value;
        const input = document.getElementById('writecomment').value;
        console.log("asdfasdf");

        $.ajax(
            {
                type: "POST",
                url:"/db/comment/postComment.php",
                data: {
                    issueId: issue_id,
                    content: input,
                },
            success: (code) => {
                switch (code) {
                    case "success":
                        window.location.reload();
                        break;
                    default:
                        console.log(code);
                        alert('오류 발생');
                }
            }
        });


    }

</script>