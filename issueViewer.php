
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

    button {
        font-size: 16px;
        width: 100px;
        height: 45px;
        border-radius: .5rem;
        border-color: #917b56;
        color: #fff;
    }
</style>

<br>
<main>
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
    echo "<script>alert('잘못된 접근입니다.');</script>";
    echo "<script>window.location.href = '/?projectId={$_GET['projectId']}&page=issue'</script>";
    exit;
}

// 이슈 ID 가 없는 이슈인가?
$issue = getIssueByIssueId($_GET['issueId']);
if (empty($issue)) {
    echo "<script>alert('없는 이슈입니다.');</script>";
    echo "<script>window.location.href = '/?projectId={$_GET['projectId']}&page=issue'</script>";
    exit;
}

// URL 의 프로젝트 ID 와 이슈의 지정된 프로젝트 ID가 다른가?
if ($_GET['projectId'] != $issue['issue_inclusion_project_id']) {
    echo "<script>alert('잘못된 접근입니다.');</script>";
    echo "<script>window.location.href = '/?projectId={$_GET['projectId']}&page=issue'</script>";
    exit;
}

// 접속중인 유저가 해당 이슈를 볼 권한이 있는가? (프로젝트에 소속되어 있는가?)
if (session_status() == PHP_SESSION_NONE)
    session_start();
$isJoin = isUserJoinedProject($issue['issue_inclusion_project_id'], $_SESSION['sess']);

if (!$isJoin) {
    echo "<script>alert('권한이 없습니다.');</script>";
    echo "<script>window.location.href = '/?projectId={$_GET['projectId']}&page=issue'</script>";
    exit;
}

// 작성자 구하기
$creator = getUserByUserId($issue['issue_creator_id']);
$creatorNickname = "알 수 없음";
if (!empty($creator))
    $creatorNickname = $creator['user_nickname'];

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
<?php echo "<button style ='width:100px;' onclick= location.href='/?projectId={$_GET['projectId']}&page=issueEditor&issueId={$issue['issue_id']}'>이슈수정</button>"; ?>

<button onclick="deleteIssue();" style="margin-left: 10px;">이슈삭제</button>
<br><br><hr><br>
<h3>이슈 본문</h3>
<hr>
<div class="article">
    <?php echo $issue['issue_article']?>
</div>
<hr>
<main>
    <div id="comments">
        <input type="hidden" id="hidden_issueId" value="<?=$issue['issue_id']?>">
        <div id="comment-head" class="comment-row">
            <span id="comments-count">2</span> Comment(s)
        </div>
        <div class="comment-row">
            <textarea name="new_comment" id="writecomment" rows="5" placeholder="New comment"></textarea>
            <button type="submit" onclick="postComment();">Submit</button>
        </div>
        <?php
        if (count($comments) == 0)
            echo "<div class='comment-content'>아직 댓글이 없어요</div>";
        else {
            foreach ($comments as $comment) {
                $commentCreator = getUserByUserId($comment['comment_creator_id']);
                $commentCreatorNickname = "알 수 없음";
                if (!empty($commentCreator))
                    $commentCreatorNickname = $commentCreator['user_nickname'];

                echo "<div class='comment-row'>";
                echo "<div class='comment-date'>{$comment['comment_create_time']}</div>";
                echo "<div class='comment-content'>{$commentCreatorNickname} : {$comment['comment_content']}</div>";
                echo "</div>";

            }
        }
        ?>
    </div>
</main>

<br>
<br>
<br>
<script type="text/javascript">

    function postComment() {
        const issue_id = document.getElementById('hidden_issueId').value;
        const input = document.getElementById('writecomment').value;

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

    function deleteIssue() {
        if (confirm('정말로 이 이슈를 삭제합니까? 이슈에 작성된 댓글또한 모두 삭제됩니다.')) {
            const issue_id = document.getElementById('hidden_issueId').value;

            $.ajax(
                {
                    type: "POST",
                    url:"/db/issue/deleteIssue.php",
                    data: {
                        issueId: issue_id,
                    },
                    success: (code) => {
                        switch (code) {
                            case "success":
                                const projectId = getParameterByName('projectId');
                                alert('이슈가 삭제되었습니다.');
                                window.location.href = 'http://localhost/?projectId=' + projectId + '&page=issue';
                                break;
                            case "access_denied":
                                alert('권한이 없습니다.');
                                break;
                            default:
                                console.log(code);
                                alert('오류 발생');
                        }
                    }
                });
        }
    }
</script>
</main>