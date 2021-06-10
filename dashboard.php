
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
if (session_status() == PHP_SESSION_NONE)
    session_start();

// 현재 접속한 유저가 프로젝트 관리자 일 경우 -> 삭제하기 버튼 출력
if ($project['project_admin_id'] == $_SESSION['sess'])
    echo "<a href='' onclick='deleteProject();'>프로젝트 삭제하기</a>";

?>
<main>
    <h3>대쉬보드</h3>
    <hr>
    <div class="setting-projectbox">
        <h3>프로젝트 정보</h3>
        <div style="padding-top: 10px;">프로젝트 이름 : <?php echo($project['project_name']); /* $project 는 index.php 에서 선언되어 내려옴 */  ?></div>
        <div>구성원 수 : </div>
        <div>시작 날짜 : </div>

    </div>
</main>