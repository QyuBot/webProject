
<?php
// URL 경로탐색으로 직접 접근할 경우 -> 404 오류 발동
if(!defined('DirectAccessCheck')){
    header('HTTP/1.0 404 Not Found', true, 404);
    exit;
}

?>
<main>
    <h3>프로젝트 제목 : <?php echo($project['project_name']); /* $project 는 index.php 에서 선언되어 내려옴 */  ?></h3>
    <hr>
    <article id="my-project">
        <h3>내 프로젝트 (DB 사용)</h3>
        <div class="my-box">
            내 프로젝트  (DB 사용)
        </div>
    </article>
    <article id="you-project">
        <h3>내가 없는 프로젝트  (DB 사용)</h3>
        <div class="you-box">
            내가 없는 프로젝트  (DB 사용)
        </div>
    </article>
    <br>
    대쉬보드를 만들어야 할 페이지
</main>