<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<style>

.article{
    margin: 2%;
    border: 1px solid black;
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
<br>
<h3>리포트 조회</h3>
<hr>
<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/db/report/reportService.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/db/project/projectService.php";

if(!defined('DirectAccessCheck')){
    header('HTTP/1.0 404 Not Found', true, 404);
    exit;
}

// 리포트 ID 매개변수가 없는가?
if (!isset($_GET['reportId'])) {
    echo "<script>alert('잘못된 접근입니다.');</script>";
    echo "<script>window.location.href = '/?projectId={$_GET['projectId']}&page=report'</script>";
    exit;
}

// 리포트 ID 가 없는 리포트인가?
$report = getReportByReportId($_GET['reportId']);
if (empty($report)) {
    echo "<script>alert('없는 리포트입니다.');</script>";
    echo "<script>window.location.href = '/?projectId={$_GET['projectId']}&page=report'</script>";
    exit;
}

// URL 의 프로젝트 ID 와 리포트의 지정된 프로젝트 ID가 다른가?
if ($_GET['projectId'] != $report['report_inclusion_project_id']) {
    echo "<script>alert('잘못된 접근입니다.');</script>";
    echo "<script>window.location.href = '/?projectId={$_GET['projectId']}&page=report'</script>";
    exit;
}

// 접속중인 유저가 해당 이슈를 볼 권한이 있는가? (프로젝트에 소속되어 있는가?)
if (session_status() == PHP_SESSION_NONE)
    session_start();
$isJoin = isUserJoinedProject($report['report_inclusion_project_id'], $_SESSION['sess']);

if (!$isJoin) {
    echo "<script>alert('권한이 없습니다.');</script>";
    echo "<script>window.location.href = '/?projectId={$_GET['projectId']}&page=report'</script>";
    exit;
}

// 작성자 구하기
$creator = getUserByUserId($report['report_creator_id']);
$creatorNickname = "알 수 없음";
if (!empty($creator))
    $creatorNickname = $creator['user_nickname'];
?>

<input type="hidden" id="hidden_reportId" value="<?=$report['report_id']?>">
<h3>리포트 제목 : <?=$report['report_title']?></h3>
<br>
<p>리포트 작성자 닉네임 : <?=$creatorNickname?></p>

<h3>리포트 본문</h3>
<hr>
<div class="article">
    <?php echo $report['report_article']?>
</div>

<?php echo "<button style='float:right' onclick=location.href='/?projectId={$_GET['projectId']}&page=reportEditor&reportId={$report['report_id']}'>수정</a>"; ?>
<button type="button" style = "margin-right: 20px; float:right" onclick="deleteReport();">삭제</button>


<br>
<br>

<script type="text/javascript">

    function deleteReport() {
        if (confirm('정말로 이 리포트를 삭제합니까?')) {
            const reportId = document.getElementById('hidden_reportId').value;

            $.ajax(
                {
                    type: "POST",
                    url:"/db/report/deleteReport.php",
                    data: {
                        reportId: reportId,
                    },
                    success: (code) => {
                        switch (code) {
                            case "success":
                                const projectId = getParameterByName('projectId');
                                alert('리포트가 삭제되었습니다.');
                                window.location.href = 'http://localhost/?projectId=' + projectId + '&page=report';
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